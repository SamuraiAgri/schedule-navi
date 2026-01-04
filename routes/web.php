<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Schedule-Naviのルート定義
|--------------------------------------------------------------------------
*/

// トップページ
Route::get('/', [EventController::class, 'index'])->name('home');

// サイトマップ
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// イベント作成
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');

// イベント作成完了
Route::get('/events/{uuid}/created', [EventController::class, 'created'])->name('events.created');

// イベント表示・回答
Route::get('/e/{uuid}', [EventController::class, 'show'])->name('events.show');
Route::post('/e/{uuid}/responses', [ResponseController::class, 'store'])
    ->name('responses.store')
    ->middleware('throttle:responses');

// イベント編集（編集キーが必要）
Route::get('/e/{uuid}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/e/{uuid}', [EventController::class, 'update'])->name('events.update');
Route::delete('/e/{uuid}', [EventController::class, 'destroy'])->name('events.destroy');

// CSV出力（編集キーが必要）
Route::get('/e/{uuid}/export', [EventController::class, 'exportCsv'])->name('events.export');

// === ヘルスチェック ===
Route::get('/health', [App\Http\Controllers\HealthController::class, 'check'])->name('health.check');
Route::get('/health/detailed', [App\Http\Controllers\HealthController::class, 'detailed'])->name('health.detailed');
