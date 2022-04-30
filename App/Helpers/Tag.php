<?php

namespace App\Helpers;

class Tag
{
    private string $name;
    private string $attribute;
    private bool $downloadable;

    /**
     * @param string $name
     * @param string $attribute
     * @param bool $downloadable
     */
    public function __construct(string $name, string $attribute, bool $downloadable = true)
    {
        $this->name = $name;
        $this->attribute = $attribute;
        $this->downloadable = $downloadable;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * @return bool
     */
    public function isDownloadable(): bool
    {
        return $this->downloadable;
    }

}