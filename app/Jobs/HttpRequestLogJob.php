<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\HTTPRequestLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HttpRequestLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id;
    private $uri;
    private $request_param;
    private $response_param;

    public function __construct($uri, $request_param, $response_param, $user_id = null)
    {
        $this->uri = $uri;
        $this->request_param = $request_param;
        $this->response_param = $response_param;
        if ($user_id !== null) {
            $this->user_id = $user_id;
        }
    }

    public function handle(): void
    {
        $requestLogs = new HTTPRequestLog();
        $requestLogs->uri = $this->uri;
        $requestLogs->request_param = $this->request_param;
        $requestLogs->response_param = $this->response_param;
        if ($this->user_id) {
            $requestLogs->user_id = $this->user_id;
        }
        $requestLogs->save();
    }
}
