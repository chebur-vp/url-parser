<?php

namespace App;

use App\Exceptions\DownloadException;
use App\Helpers\Downloader;
use App\Helpers\Saver;
use App\Helpers\Tag;

class App
{
    private \DOMDocument $dom;
    private Helpers\Saver $saver;
    private string $url;
    /**
     * @var Tag[] $tags
     */
    private array $tags = [];
    private array $results = [];

    /**
     * @param string $url
     * @throws \Exception
     */
    public function __construct(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("URL '$url' is not valid.");
        }
        $this->url = $url;
        $this->dom = new \DOMDocument();
        $this->saver = new Saver($this->url);
    }

    /**
     * @param string $tag
     * @param string $attribute
     * @param bool $downloadable
     * @return $this
     */
    public function addTag(string $tag, string $attribute, bool $downloadable = true): App
    {
        $this->tags[] = new Tag($tag, $attribute, $downloadable);
        return $this;
    }

    /**
     * @param $url
     * @return $this
     * @throws \Exception
     */
    public function setSavePath($url): App
    {
        $this->saver->setSavePath($url);
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function load(): App
    {
        $response = Downloader::get($this->url);
        if (!$response->isValid()) {
            throw new DownloadException($this->url, "Error when attempting to get HTML: {$response->getCurlErrorMessage()}'.", $response->getCurlErrorCode());
        }
        $this->dom->loadHTML($response->getContent(), LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_PARSEHUGE | LIBXML_NOBLANKS | LIBXML_COMPACT);

        /** @var \DOMNode[] $baseTags */
        $baseTags = $this->dom->getElementsByTagName('base');
        if (isset($baseTags[0])) {
            /** @var \DOMAttr $baseUrl */
            $baseUrl = $baseTags[0]->attributes->getNamedItem('href');
            if ($baseUrl) {
                $this->saver->setBaseUrl(str_ends_with($baseUrl->value, '/') ? $baseUrl->value : dirname($baseUrl->value));
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function run(): App
    {
        foreach ($this->tags as $tag) {
            /** @var \DOMNode $item */
            foreach ($this->dom->getElementsByTagName($tag->getName()) as $item) {
                /** @var \DOMAttr $attribute */
                if ($attribute = $item->attributes->getNamedItem($tag->getAttribute())) {
                    $this->results[$tag->getName()][] = $attribute->value;
                }
            }
        }
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function download(): App
    {
        foreach ($this->tags as $tag) {
            if ($tag->isDownloadable() && isset($this->results[$tag->getName()])) {
                foreach ($this->results[$tag->getName()] as $url) {
                    $this->saver->download($url);
                }
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

}

