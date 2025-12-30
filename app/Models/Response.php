<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 回答モデル
 * 参加者の出欠回答を管理
 */
class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'participant_name',
        'participant_email',
        'comment',
        'ip_address',
        'user_agent',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    // ===== リレーション =====

    /**
     * イベントとの関連
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 回答詳細との関連
     */
    public function details(): HasMany
    {
        return $this->hasMany(ResponseDetail::class);
    }

    // ===== アクセサ =====

    /**
     * 表示用の参加者名を取得
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? '匿名さん' : $this->participant_name;
    }

    // ===== メソッド =====

    /**
     * 特定の日程に対するステータスを取得
     */
    public function getStatusForDate(int $eventDateId): ?string
    {
        $detail = $this->details->firstWhere('event_date_id', $eventDateId);
        return $detail?->status;
    }

    /**
     * 回答詳細をマップ形式で取得
     * [event_date_id => status]
     */
    public function getDetailsMap(): array
    {
        return $this->details->pluck('status', 'event_date_id')->toArray();
    }
}
