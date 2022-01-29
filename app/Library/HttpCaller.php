<?php

declare(strict_types=1);

namespace App\Library;

use App\Jobs\HttpRequestLogJob;
use App\Library\Common\Caller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpCaller implements Caller
{
    private array $headers;
    private string $baseUrl;

    public function __construct($baseUrl = null)
    {
        $this->init();

        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function getHeader(): array
    {
        $locale = app()->getLocale() ?? 'en';
        $this->setHeader('Content-Language', $locale);
        return $this->headers;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function makePostRequest($url, $payload, $contentType = 'application/json')
    {
        $response = Http::withHeaders($this->getHeader())
            ->withHeaders([
                'Content-Type' => $contentType,
            ])
            ->post(
                $this->getBaseUrl() . $url,
                $payload
            );

        Log::info($response->body());

        HttpRequestLogJob::dispatch(
            $this->getBaseUrl() . $url,
            json_encode($payload),
            $response->body(),
            auth()->check() ? auth()->user()->id : null
        );

        if ($response->failed()) {
            $response->throw();
        }

        return $response->body();
    }

    public function makeGetRequest($url, $params = [], $passBody = false)
    {
        $response = Http::withHeaders($this->getHeader())
            ->get(
                $this->getBaseUrl() . $url,
                $params
            );

        Log::info($response->body());

        HttpRequestLogJob::dispatch(
            $this->getBaseUrl() . $url,
            json_encode($params),
            $response->body(),
            auth()->check() ? auth()->user()->id : null
        );

        if ($response->failed()) {
            $response->throw();
        }

        if ($passBody) {
            return $response;
        }

        return $response->body();
    }

    private function init(): void
    {
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Content-Language' => 'en',
        ];
    }
}
