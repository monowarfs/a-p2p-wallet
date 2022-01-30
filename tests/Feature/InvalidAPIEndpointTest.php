<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvalidAPIEndpointTest extends TestCase
{
    public function test_response()
    {
        $endPoints = Str::random(10);

        $this->json('get', $endPoints)
            ->assertJson([
                "code" => 404,
                'messages' => ['Invalid Endpoint'],
                'data' => null,
            ]);
    }


}
