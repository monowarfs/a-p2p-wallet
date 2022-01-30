<?php

namespace Tests;

use Laravel\Passport\Passport;

class AuthTestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = $this->getOneUser();
        Passport::actingAs($user);
        $token = $user->createToken('TEST')->accessToken;
        $this->setHeader('Authorization', "Bearer ".$token);
    }
}
