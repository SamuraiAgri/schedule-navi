<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * イベントモデル
 * 日程調整イベントの基本情報を管理
 */
class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'notify_email',
        'is_time_required',
        'is_anonymous_allowed',
        'max_participants',
        'deadline_at',
        'status',
    ];

    protected $casts = [
        'is_time_required' => 'boolean',
        'is_anonymous_allowed' => 'boolean',
        'deadline_at' => 'datetime',
        'view_count' => 'integer',
        'max_participants' => 'integer',
    ];

    /**
     * モデル生成時にUUIDと編集キーを自動生成
     */
    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            $event->uuid = $event->uuid ?? Str::uuid()->toString();
            $event->edit_key = $event->edit_key ?? hash('sha256', Str::random(32));
        });
    }

    // ===== リレーション =====

    /**
     * 候補日程との関連
     */
    public function dates(): HasMany
    {
        return $this->hasMany(EventDate::class)->orderBy('sort_order')->orderBy('date');
    }

    /**
     * 回答との関連
     */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class)->orderBy('created_at');
    }

    // ===== アクセサ =====

    /**
     * 共有URLを取得
     */
    public function getShareUrlAttribute(): string
    {
        return route('events.show', $this->uuid);
    }

    /**
     * 編集URLを取得
     */
    public function getEditUrlAttribute(): string
    {
        return route('events.edit', ['uuid' => $this->uuid, 'key' => $this->edit_key]);
    }

    /**
     * 回答期限切れかどうか
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->deadline_at && $this->deadline_at->isPast();
    }

    /**
     * アクティブなイベントかどうか
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && !$this->is_expired;
    }

    // ===== スコープ =====

    /**
     * アクティブなイベントのみ取得
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ===== メソッド =====

    /**
     * 閲覧数をインクリメント
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * 編集キーが正しいか検証
     */
    public function verifyEditKey(string $key): bool
    {
        return hash_equals($this->edit_key, $key);
    }

    /**
     * UUIDでイベントを検索
     */
    public static function findByUuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }

    /**
     * UUIDでイベントを検索（見つからない場合は404）
     */
    public static function findByUuidOrFail(string $uuid): self
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }
}
