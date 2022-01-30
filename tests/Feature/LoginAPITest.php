<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Tests\BaseTestCase;

class LoginAPITest extends BaseTestCase
{
    public function test_must_submit_mobile_number_and_password_for_login()
    {
        $this->json('post', APIEndpoints::V1_AUTH_SIGN_IN)
            ->assertStatus(200)
            ->assertJson([
                "code" => 422,
                "messages" => [
                    "The mobile number field is required.",
                    "The password field is required."
                ],
                'data' => [],
            ]);
    }

    public function test_successful_login()
    {
        // using the seeder data
        $this->login($this->getOneUser())
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'messages',
                'data' => [
                    'token'
                ]
            ]);

        $this->assertAuthenticated();
    }
}
