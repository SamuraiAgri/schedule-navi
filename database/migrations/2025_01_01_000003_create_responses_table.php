<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * responsesテーブルのマイグレーション
 * 参加者の回答情報を管理
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('participant_name', 100)->comment('参加者名');
            $table->string('participant_email', 255)->nullable()->comment('参加者メール');
            $table->text('comment')->nullable()->comment('コメント');
            $table->string('ip_address', 45)->nullable()->comment('IPアドレス');
            $table->string('user_agent', 255)->nullable()->comment('ユーザーエージェント');
            $table->boolean('is_anonymous')->default(false)->comment('匿名回答');
            $table->timestamps();

            $table->index('event_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
