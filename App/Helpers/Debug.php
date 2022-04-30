<?php

namespace App\Helpers;

class Debug
{
    const ESC = "\033";

    private static array $styles = [
        'reset' => '0',
        'error' => '1;31',
        'warning' => '1;36',
        'info' => '1;32',
        'notice' => '1;30',
    ];

    private static function makeEscSequence(string $color)
    {
        return self::ESC . '[' . self::$styles[$color] . 'm';
    }

    /**
     * @param mixed ...$vars
     * @return void
     */
    public static function show(mixed ...$vars): void
    {
        if (defined('SHOW_ADDITIONAL_INFO') && SHOW_ADDITIONAL_INFO) {
            dump($vars);
        }
    }

    /**
     * @param mixed ...$vars
     * @return void
     */
    public static function wtf(mixed ...$vars): void
    {
        if (defined('SHOW_ADDITIONAL_INFO') && SHOW_ADDITIONAL_INFO) {
            dd($vars);
        }
    }

    private static function message($text, $method = 'info')
    {
        echo self::makeEscSequence($method) . $text . self::makeEscSequence('reset') . PHP_EOL;
    }

    /**
     * @param string $text
     * @return void
     */
    public static function error(string $text): void
    {
        self::message($text, 'error');
    }

    /**
     * @param string $text
     * @return void
     */
    public static function warning(string $text): void
    {
        self::message($text, 'warning');
    }

    /**
     * @param string $text
     * @return void
     */
    public static function info(string $text): void
    {
        self::message($text, 'info');
    }

    /**
     * @param string $text
     * @return void
     */
    public static function notice(string $text): void
    {
        self::message($text, 'notice');
    }


}