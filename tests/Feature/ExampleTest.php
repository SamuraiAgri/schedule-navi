<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * アプリケーションの基本的なヘルスチェックテスト
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * アプリケーション環境設定のテスト
     */
    public function test_application_environment_is_configured(): void
    {
        $this->assertNotEmpty(config('app.name'));
        $this->assertNotEmpty(config('app.key'));
    }
}
