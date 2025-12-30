<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 回答詳細モデル
 * 各日程に対する出欠ステータスを管理
 */
class ResponseDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'response_id',
        'event_date_id',
        'status',
    ];

    /**
     * ステータス定数
     */
    public const STATUS_OK = 'ok';
    public const STATUS_NG = 'ng';
    public const STATUS_MAYBE = 'maybe';

    /**
     * ステータスラベルのマッピング
     */
    public const STATUS_LABELS = [
        self::STATUS_OK => '○',
        self::STATUS_NG => '×',
        self::STATUS_MAYBE => '△',
    ];

    // ===== リレーション =====

    /**
     * 回答との関連
     */
    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class);
    }

    /**
     * イベント日程との関連
     */
    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class);
    }

    // ===== アクセサ =====

    /**
     * ステータスラベルを取得
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? '-';
    }

    /**
     * ステータスに対応するCSSクラスを取得
     */
    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OK => 'text-green-600 bg-green-100',
            self::STATUS_NG => 'text-red-600 bg-red-100',
            self::STATUS_MAYBE => 'text-yellow-600 bg-yellow-100',
            default => 'text-gray-400 bg-gray-100',
        };
    }
}
