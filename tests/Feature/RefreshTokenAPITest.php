<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\AuthTestCase;

class RefreshTokenAPITest extends AuthTestCase
{
    public function test_get_the_refresh_token()
    {
        $this->withHeaders($this->getHeader())
            ->json(
                'GET',
                APIEndpoints::V1_AUTH_REFRESH_TOKEN
            )
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'messages',
                'data' => [
                    'token'
                ]
            ])
            ->assertJson(function (AssertableJson $json){
                $json->where('code', 200)->etc();
                $json->has('data.token');
            });
    }
}
