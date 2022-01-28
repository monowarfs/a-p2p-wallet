<?php

namespace App\Library\CommonInterfaces;

interface CallerInterface
{
    public function setHeader($key, $value);
    public function getHeader(): array;
    public function setBaseUrl($baseUrl);
    public function getBaseUrl();
    public function makePostRequest($url, $payload, $contentType);
    public function makeGetRequest($url, $params);
}
