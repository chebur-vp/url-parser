<?php

namespace App\Exceptions;

class DownloadException extends \Exception
{

    /**
     * @param string $url
     * @param string $message
     * @param int $code
     */
    public function __construct(string $url, string $message = '', int $code = 0)
    {
        return parent::__construct("File '$url' download error: '$message'", $code);
    }
}