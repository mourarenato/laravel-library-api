<?php

namespace App\Tests\Integration;

use App\Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/index');

        $response->assertStatus(200);
    }
}
