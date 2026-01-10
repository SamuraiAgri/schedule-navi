<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * テスト実行前のセットアップ
     */
    protected function setUp(): void
    {
        parent::setUp();

        // CI環境でVite manifestがない場合のダミー設定
        if (!file_exists(public_path('build/manifest.json'))) {
            $this->withoutVite();
        }
    }
}
