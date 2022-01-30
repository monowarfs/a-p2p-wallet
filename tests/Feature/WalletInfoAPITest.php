<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\AuthTestCase;

class WalletInfoAPITest extends AuthTestCase
{
    public function test_get_the_wallet_info()
    {
        $this->withHeaders($this->getHeader())
            ->json(
                'get',
                APIEndpoints::V1_USER_WALLET_INFORMATION
            )
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json->where('code', 200)->etc();
            });
    }
}
