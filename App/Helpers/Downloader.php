<?php

namespace App\Helpers;

class Downloader
{

    /**
     * @param string $url
     * @return HttpResponse|false
     */
    public static function get(string $url): HttpResponse|false {
        $result = new HttpResponse();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result->setContent(curl_exec($ch));

        if (curl_errno($ch)) {
            curl_close($ch);
            $result
                ->setCurlErrorCode(curl_errno($ch))
                ->setCurlErrorMessage(curl_error($ch));
            return $result;
        }

        $result->setHttpCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        $result->setHttpMessage("HTTP response {$result->getHttpCode()}");
        $result->setContentType(curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?? '');
        curl_close($ch);

        return $result;
    }
}