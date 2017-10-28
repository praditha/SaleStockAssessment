<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/login', [
        	"email" => "admin@salestock.com",
        	"password" => "secret"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Login successfully.',
            ]);

        $json = json_decode($response->getContent());
        $this->assertTrue(!empty($json->data->token));
    }

    public function testUserLogout() {
    	$response = $this->json('POST', '/api/login', [
        	"email" => "admin@salestock.com",
        	"password" => "secret"
        ]);

        $json = json_decode($response->getContent());

    	$response = $this->withHeaders([
    		'Authorization' => $json->data->token
    	])->json('GET', '/api/logout');

    	$response->assertStatus(200)
            ->assertJson([
                'message' => 'The user has been successfully logged out.',
            ]);
    }
}
