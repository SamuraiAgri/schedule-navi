<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageAccessTest extends TestCase
{
    /**
     * トップページへのアクセステスト
     */
    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * イベント作成ページへのアクセステスト
     */
    public function test_event_create_page_is_accessible(): void
    {
        $this->get('/events/create')->assertStatus(200);
    }

    /**
     * 静的ページへのアクセステスト（テスト環境用）
     */
    public function test_static_pages_are_accessible(): void
    {
        $this->get('/privacy')->assertStatus(200);
        $this->get('/terms')->assertStatus(200);
        $this->get('/about')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
    }

    /**
     * サイトマップへのアクセステスト
     */
    public function test_sitemap_is_accessible(): void
    {
        $this->get('/sitemap.xml')->assertStatus(200);
    }

    /**
     * ヘルスチェックエンドポイントのテスト
     */
    public function test_health_check_endpoints_are_accessible(): void
    {
        $this->get('/health')->assertStatus(200);
        $this->get('/health/detailed')->assertStatus(200);
    }
}
