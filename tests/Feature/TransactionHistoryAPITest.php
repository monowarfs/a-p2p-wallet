<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\AuthTestCase;

class TransactionHistoryAPITest extends AuthTestCase
{
    public function test_get_the_transaction_history_list()
    {
        $this->withHeaders($this->getHeader())
            ->json(
                'get',
                APIEndpoints::V1_USER_TRANSACTION_HISTORY
            )
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json->where('code', 200)->etc();
            });
    }
}
