<?php

namespace App\Helpers;

class HttpResponse
{
    private string $content;
    private string $contentType;
    private int $httpCode;
    private string $httpMessage;
    private int $curlErrorCode = 0;
    private string $curlErrorMessage;
    private bool $valid = true;

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     * @return void
     */
    private function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * @return string
     */
    public function getHttpMessage(): string
    {
        return $this->httpMessage;
    }

    /**
     * @param string $httpMessage
     * @return HttpResponse
     */
    public function setHttpMessage(string $httpMessage): HttpResponse
    {
        $this->httpMessage = $httpMessage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurlErrorCode(): int
    {
        return $this->curlErrorCode;
    }

    /**
     * @param int $curlErrorCode
     * @return HttpResponse
     */
    public function setCurlErrorCode(int $curlErrorCode): HttpResponse
    {
        $this->curlErrorCode = $curlErrorCode;
        $this->setValid($this->curlErrorCode === 0);
        return $this;
    }

    /**
     * @return string
     */
    public function getCurlErrorMessage(): string
    {
        return $this->curlErrorMessage;
    }

    /**
     * @param string $curlErrorMessage
     * @return HttpResponse
     */
    public function setCurlErrorMessage(string $curlErrorMessage): HttpResponse
    {
        $this->curlErrorMessage = $curlErrorMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return HttpResponse
     */
    public function setContent(string $content): HttpResponse
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @return HttpResponse
     */
    public function setContentType(string $contentType): HttpResponse
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @param int $httpCode
     * @return HttpResponse
     */
    public function setHttpCode(int $httpCode): HttpResponse
    {
        $this->httpCode = $httpCode;
        // Accept only 2XX HTTP codes
        $this->setValid(intdiv($this->httpCode, 100) === 2);
        return $this;
    }


}