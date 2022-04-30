<?php

namespace App;

use App\Helpers\Scheme;
use App\Exceptions\DownloadException;

class URL
{
    private string $originalUrl;

    private Scheme $scheme;
    private string|null $host;
    private int|null $port;
    private string|null $user;
    private string|null $pass;
    private string|null $path;
    private array $query = [];
    private string|null $fragment;

    /**
     * @param string $url
     * @param bool $stopOnFail
     * @throws DownloadException
     */
    public function __construct(string $url, bool $stopOnFail = false)
    {
        $this->originalUrl = $url;
        $scheme = parse_url($this->originalUrl, PHP_URL_SCHEME) ?? '';
        $this->scheme = Scheme::tryFrom(strtolower($scheme)) ?? Scheme::HTTP;
        $this->host = parse_url($this->originalUrl, PHP_URL_HOST);
        $this->port = parse_url($this->originalUrl, PHP_URL_PORT);
        $this->user = parse_url($this->originalUrl, PHP_URL_USER);
        $this->pass = parse_url($this->originalUrl, PHP_URL_PASS);
        $this->path = parse_url($this->originalUrl, PHP_URL_PATH) ?? '/';
        parse_str(parse_url($this->originalUrl, PHP_URL_QUERY) ?? '', $this->query);
        $this->fragment = parse_url($this->originalUrl, PHP_URL_FRAGMENT);
        if ($stopOnFail && !$this->host && !$this->path) {
            throw new DownloadException($url, 'Bad URL.');
        }
    }

    /**
     * @return Scheme
     */
    public function getScheme(): Scheme
    {
        return $this->scheme;
    }

    /**
     * @return string|null
     */
    public function getHost(): string|null
    {
        return $this->host;
    }

    /**
     * @return string|null
     */
    public function getPort(): string|null
    {
        return $this->port;
    }

    /**
     * @return string|null
     */
    public function getUser(): string|null
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getPass(): string|null
    {
        return $this->pass;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @return string|null
     */
    public function getFragment(): string|null
    {
        return $this->fragment;
    }


    /**
     * @return string
     */
    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    /**
     * @return string|null
     */
    public function getPath(): string|null
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        $url = "{$this->scheme->value}:";
        if (($this->user && $this->pass) || $this->host || $this->port) {
            $url .= '//';
            if ($this->user) {
                $url .= $this->user;
                if ($this->pass) {
                    $url .= ":$this->pass";
                }
                $url .= '@';
            }
            if ($this->host) {
                $url .= $this->host;
            }
            if ($this->port) {
                $url .= ":$this->port";
            }
        }
        $url .= $this->path;
        if ($this->query) {
            $url .= '?' . urldecode(http_build_query($this->query));
        }
        if ($this->fragment) {
            $url .= "#$this->fragment";
        }

        return $url;
    }

    /**
     * @param URL $url
     * @return $this
     */
    public function fixBaseUrl(URL $url): URL {
        $slash = '/';
        $this->host = $this->host ?? $url->host;
        $this->port = $this->port ?? $url->port;
        $this->user = $this->user ?? $url->user;
        $this->pass = $this->pass ?? $url->pass;
        if (preg_match_all('/^\/[^\/]?.*$/m', $this->path) === 0) {
            $this->path = rtrim($url->path, $slash) . $slash . $this->path;
        }
        return $this;
    }

}