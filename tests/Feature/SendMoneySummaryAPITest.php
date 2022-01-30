<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\AuthTestCase;

class SendMoneySummaryAPITest extends AuthTestCase
{
    public function test_must_submit_receiver_mobile_number_amount_for_summary()
    {
        $this->withHeaders($this->getHeader())
            ->json('post', APIEndpoints::V1_SEND_MONEY_STEP1)
            ->assertStatus(200)
            ->assertJson([
                "code" => 422,
                "messages" => [
                    "The receiver mobile number field is required.",
                    "The amount field is required."
                ],
                'data' => [],
            ]);
    }

    public function test_send_money_summary_api()
    {
        $this->withHeaders($this->getHeader())
            ->json(
                'post',
                APIEndpoints::V1_SEND_MONEY_STEP1,
                [
                    'receiver_mobile_number' => '+8801914000001',
                    'amount' => 100
                ]
            )
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json->where('code', 200)->etc();
            });
    }
}
