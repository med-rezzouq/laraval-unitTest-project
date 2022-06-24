<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;
use Google\Client;

class ServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setup(): void
    {
        parent::setup();
        $this->user = $this->authUser();
    }
    public function test_a_user_can_connect_to_a_service_and_token_is_stored()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes');
            $mock->shouldReceive('createAuthUrl')->andReturn('http://localhost');
        });

        $response = $this->getJson(route('web-service.connect', 'google-drive'))->assertOk()->json();

        $this->assertEquals($response['url'], 'http://localhost');
        $this->assertNotNull($response['url']);
        return $response;
    }

    public function test_service_callback_will_store_token()
    {

        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')->andReturn('fake-token');
        });


        $res = $this->postJson(route('web-service.callback'), ['code' => 'dummyCode'])->assertCreated();

        //access_token, id and secret
        //token field, as a json

        //web_services here is a table in db

        $this->assertDatabaseHas('web_services', [
            'user_id' => $this->user->id,
            // 'token' => "{\"access_token\":\"fake-token\"}",
            'token' => "\"{\\\"access_token\\\":\\\"fake-token\\\"}\""
        ]);
        // $this->assertNotNull($this->user->services->first()->token);
    }

    public function test_data_of_a_week_can_be_stored_on_google_drive()
    {
        $this->createTask(['created_at' => now()->subDays(2)]);
        $this->createTask(['created_at' => now()->subDays(3)]);
        $this->createTask(['created_at' => now()->subDays(4)]);
        $this->createTask(['created_at' => now()->subDays(6)]);
        $this->createTask(['created_at' => now()->subDays(10)]);

        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('getLogger->info');
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
        });

        $web_service = $this->createWebService();
        $this->postJson(route('web-service.store', $web_service->id))->assertCreated();
    }
}
