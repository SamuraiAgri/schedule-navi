<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * response_detailsテーブルのマイグレーション
 * 各日程に対する出欠詳細を管理
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('response_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_date_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['ok', 'ng', 'maybe'])->comment('出欠: ○, ×, △');

            $table->unique(['response_id', 'event_date_id'], 'unique_response_date');
            $table->index('response_id');
            $table->index('event_date_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('response_details');
    }
};
