<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    /**
     * 基本的なヘルスチェック（死活監視用）
     */
    public function check(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * 詳細なヘルスチェック（DB接続含む）
     */
    public function detailed(): JsonResponse
    {
        $checks = [];
        $healthy = true;

        // アプリケーション状態
        $checks['app'] = [
            'status' => 'ok',
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
        ];

        // データベース接続チェック
        try {
            DB::connection()->getPdo();
            $checks['database'] = [
                'status' => 'ok',
                'connection' => config('database.default'),
            ];
        } catch (\Exception $e) {
            $checks['database'] = [
                'status' => 'error',
                'message' => 'Database connection failed',
            ];
            $healthy = false;
        }

        // キャッシュチェック（オプション）
        try {
            Cache::put('health_check', true, 10);
            $cacheWorking = Cache::get('health_check') === true;
            $checks['cache'] = [
                'status' => $cacheWorking ? 'ok' : 'error',
                'driver' => config('cache.default'),
            ];
            if (!$cacheWorking) {
                $healthy = false;
            }
        } catch (\Exception $e) {
            $checks['cache'] = [
                'status' => 'error',
                'message' => 'Cache check failed',
            ];
            $healthy = false;
        }

        // ストレージチェック
        $storagePath = storage_path('logs');
        $checks['storage'] = [
            'status' => is_writable($storagePath) ? 'ok' : 'error',
            'path' => 'storage/logs',
        ];
        if (!is_writable($storagePath)) {
            $healthy = false;
        }

        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }
}
