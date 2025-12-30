<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * event_datesテーブルのマイグレーション
 * イベントの候補日程を管理
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->date('date')->comment('候補日');
            $table->time('time_from')->nullable()->comment('開始時刻');
            $table->time('time_to')->nullable()->comment('終了時刻');
            $table->unsignedInteger('sort_order')->default(0)->comment('表示順');
            $table->timestamps();

            $table->index('event_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_dates');
    }
};
