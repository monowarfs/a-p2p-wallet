<?php

namespace Tests\Feature;

use App\Constant\APIEndpoints;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\AuthTestCase;

class StatementAPITest extends AuthTestCase
{
    public function test_get_the_statement_list()
    {
        $this->withHeaders($this->getHeader())
            ->json(
                'get',
                APIEndpoints::V1_USER_STATEMENTS
            )
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json->where('code', 200)->etc();
            });
    }
}
