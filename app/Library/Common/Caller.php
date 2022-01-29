<?php

declare(strict_types=1);

namespace App\Library\Common;

interface Caller
{
    public function setHeader($key, $value);
    public function getHeader();
    public function setBaseUrl($baseUrl);
    public function getBaseUrl();
    public function makePostRequest($url, $payload, $contentType);
    public function makeGetRequest($url, $params);
}
