<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * eventsテーブルのマイグレーション
 * イベント（日程調整）の基本情報を管理
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('URL用のUUID');
            $table->string('edit_key', 64)->comment('編集用秘密キー');
            $table->string('title', 255)->comment('イベント名');
            $table->text('description')->nullable()->comment('イベント説明');
            $table->string('notify_email', 255)->nullable()->comment('通知先メールアドレス');
            $table->boolean('is_time_required')->default(false)->comment('時間指定の有無');
            $table->boolean('is_anonymous_allowed')->default(true)->comment('匿名回答を許可');
            $table->integer('max_participants')->nullable()->comment('参加人数上限');
            $table->dateTime('deadline_at')->nullable()->comment('回答期限');
            $table->enum('status', ['active', 'closed', 'deleted'])->default('active');
            $table->unsignedInteger('view_count')->default(0)->comment('閲覧数');
            $table->timestamps();
            $table->softDeletes();

            $table->index('uuid');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
