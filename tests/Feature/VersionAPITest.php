<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Tests\TestCase;

class VersionAPITest extends TestCase
{
    public function test_response_data_structure()
    {
        $this->json('get', APIEndpoints::VERSION)
            ->assertStatus(200)
            ->assertJsonStructure([
                "code",
                "messages",
                "data" => [
                    'server_time',
                    'api_version',
                ]
            ]);
    }
}
