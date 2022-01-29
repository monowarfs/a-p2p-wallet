<?php

declare(strict_types=1);

namespace App\Library\Common;

interface Caller
{
    public function setHeader($key, $value): void;
    public function getHeader(): array;
    public function setBaseUrl($baseUrl): void;
    public function getBaseUrl(): void;
    public function makePostRequest($url, $payload, $contentType): void;
    public function makeGetRequest($url, $params): void;
}
