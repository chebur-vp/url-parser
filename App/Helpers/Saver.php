<?php

namespace App\Helpers;

use App\URL;

class Saver
{
    private const ALLOWED_MIME_TYPES = [
        'audio/*',
        'font/*',
        'image/*',
        'text/*',
        'video/*',
        'application/javascript',
        'application/x-javascript',
        'application/json',
    ];

    private string $savePath = './downloads';
    private URL $baseUrl;

    /**
     * @param string $baseUrl
     * @throws \Exception
     */
    public function __construct(string $baseUrl)
    {
        $this->setBaseUrl($baseUrl);
    }

    /**
     * @param string $baseUrl
     * @return Saver
     * @throws \App\Exceptions\DownloadException
     */
    public function setBaseUrl(string $baseUrl): Saver
    {
        $this->baseUrl = new URL($baseUrl, true);
        return $this;
    }

    /**
     * @param string $savePath
     * @return Saver
     * @throws \Exception
     */
    public function setSavePath(string $savePath): Saver
    {
        $this->savePath = $savePath;
        return $this;
    }

    /**
     * @param string $path
     * @return bool
     */
    private function checkSavePath(string $path): bool
    {
        if (file_exists($path)) {
            if (is_file($path)) {
                return false;
            }
            return true;
        }
        return mkdir($path, 0777, true);
    }

    /**
     * @param string $contentType
     * @return bool
     */
    private function isAllowedContentType(string $contentType): bool
    {
        foreach (self::ALLOWED_MIME_TYPES as $mimeType) {
            $regex = '/^' . str_replace('/', '\/', $mimeType) . '/i';
            if (preg_match($regex, $contentType) === 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $path
     * @return string
     * @throws \Exception
     */
    private function getPath($path): string {
        $url = new URL($path);
        return $url->getPath();
    }

    /**
     * @param URL $url
     * @return string
     * @throws \Exception
     */
    private function getFileName(URL $url): string
    {
        if (str_ends_with($url->getPath(), '/')) {
            return '';
        }
        return pathinfo($url->getPath(), PATHINFO_BASENAME);
    }

    /**
     * @param string $url
     * @return bool
     * @throws \Exception
     */
    public function download(string $url): bool
    {
        $urlToSave = new URL($url);
        $url = $urlToSave->fixBaseUrl($this->baseUrl)->getUrl();

        $response = Downloader::get($url);
        if ($response->getCurlErrorCode()) {
            Debug::error("ERROR CODE {$response->getCurlErrorCode()} $url");
            return false;
        }

        if (!$this->isAllowedContentType($response->getContentType())) {
            Debug::error("CONTENT TYPE {$response->getContentType()}");
            return false;
        }

        $fileName = $this->getFileName($urlToSave);
        if ($fileName === '') {
            Debug::error("EMPTY FILENAME $url");
            return false;
        }

        return $this->save($response->getContent(), $urlToSave->getHost() . '/' . $this->getPath($url));
    }

    /**
     * @param string $content
     * @param string $path
     * @return int|bool
     * @throws \Exception
     */
    private function save(string $content, string $path): int|bool
    {
        $savePath = str_replace('//', '/', $this->savePath . DIRECTORY_SEPARATOR . $path);
        $pathInfo = pathinfo($savePath);
        if (!$this->checkSavePath($pathInfo['dirname'])) {
            throw new \Exception("Cannot save to '$savePath'");
        }

        // Avoid duplication of file names
        $counter = 1;
        while (file_exists($savePath)) {
            $savePath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '_' . $counter++ . '.' . ($pathInfo['extension'] ?? '');
        }

        return file_put_contents($savePath, $content);
    }

}