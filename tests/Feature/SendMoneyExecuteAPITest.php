<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\AuthTestCase;

class SendMoneyExecuteAPITest extends AuthTestCase
{
    public function test_must_submit_receiver_mobile_number_amount_and_pin_for_execute()
    {
        $this->withHeaders($this->getHeader())
            ->json('post', APIEndpoints::V1_SEND_MONEY_STEP2)
            ->assertStatus(200)
            ->assertJson([
                "code" => 422,
                "messages" => [
                    "The receiver mobile number field is required.",
                    "The amount field is required.",
                    "The pin field is required."
                ],
                'data' => [],
            ]);
    }

    public function test_amount_must_be_non_negative_to_execute()
    {
        $this->withHeaders($this->getHeader())
            ->json(
            'post',
            APIEndpoints::V1_SEND_MONEY_STEP2,
                [
                    'receiver_mobile_number' => '+8801914000001',
                    'amount' => -100,
                    'pin' => 1234
                ]
            )
            ->assertStatus(200)
            ->assertJson([
                "code" => 422,
                "messages" => [
                    "The amount must be at least 1."
                ],
                'data' => [],
            ]);
    }

    public function test_wallet_must_have_sufficient_balance_to_transfer()
    {
        $this->withHeaders($this->getHeader())
            ->json('post', APIEndpoints::V1_SEND_MONEY_STEP2,
                [
                    'receiver_mobile_number' => '+8801914000001',
                    'amount' => 100000,
                    'pin' => 1234
                ])
            ->assertStatus(200)
            ->assertJson([
                "code" => 422,
                "messages" => [
                    "Sorry! You do not have sufficient amount."
                ],
                'data' => null,
            ]);
    }

    public function test_send_money_execute_api()
    {
        $this->withHeaders($this->getHeader())
            ->json(
                'post',
                APIEndpoints::V1_SEND_MONEY_STEP2,
                [
                    'receiver_mobile_number' => '+8801914000001',
                    'amount' => 100,
                    'pin' => 1234
                ]
            )
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'messages',
                'data' => [
                    'summary' => [
                        "receiver" =>[
                            "user" =>[
                                "name",
                                "email",
                                "mobile_number"
                            ]
                        ],
                        "conversion_rate",
                        "amount",
                        "charge",
                        "total_payable",
                        "total_receivable",
                        "invoice_id"
                    ]
                ]
            ])
            ->assertJson(function (AssertableJson $json){
                $json->where('code', 200)->etc();
            });
    }
}
