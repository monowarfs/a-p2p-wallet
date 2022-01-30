<?php

namespace Tests;

use App\Constant\APIEndpoints;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;

class BaseTestCase extends TestCase
{
    use WithFaker;

    public const PASSWORD = 'Password100@';
    public const PIN = '1234';
    public $defaultHeaders = [
      'Accept' => 'application/json',
      'Content-Type' => 'json'
    ];

    public function getHeader(): array
    {
        return $this->defaultHeaders;
    }

    public function setHeader($key, $value): void
    {
        $this->defaultHeaders[$key] = $value;
    }

    public function getOneUser()
    {
        return User::where('mobile_no', '+8801914000000')->first();
    }

    public function login(User $user): TestResponse
    {
        return $this->withHeaders($this->getHeader())
                ->json('POST',
                APIEndpoints::V1_AUTH_SIGN_IN,
                [
                    'mobile_number' => $user->mobile_no,
                    'password' => self::PASSWORD
                ]);

    }

}
