<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * イベント候補日程モデル
 * 各イベントの候補日時を管理
 */
class EventDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'date',
        'time_from',
        'time_to',
        'sort_order',
    ];

    protected $casts = [
        'date' => 'date',
        'time_from' => 'datetime:H:i',
        'time_to' => 'datetime:H:i',
        'sort_order' => 'integer',
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
    public function responseDetails(): HasMany
    {
        return $this->hasMany(ResponseDetail::class);
    }

    // ===== アクセサ =====

    /**
     * フォーマット済みの日付を取得
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('Y/m/d') . '(' . $this->getWeekdayName() . ')';
    }

    /**
     * フォーマット済みの時間範囲を取得
     */
    public function getFormattedTimeAttribute(): ?string
    {
        if (!$this->time_from) {
            return null;
        }

        $from = Carbon::parse($this->time_from)->format('H:i');
        $to = $this->time_to ? Carbon::parse($this->time_to)->format('H:i') : null;

        return $to ? "{$from}〜{$to}" : $from;
    }

    /**
     * 完全なラベル（日付+時間）を取得
     */
    public function getFullLabelAttribute(): string
    {
        $label = $this->formatted_date;
        if ($this->formatted_time) {
            $label .= ' ' . $this->formatted_time;
        }
        return $label;
    }

    // ===== ヘルパー =====

    /**
     * 曜日名を取得
     */
    private function getWeekdayName(): string
    {
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        return $weekdays[$this->date->dayOfWeek];
    }

    /**
     * 各ステータスの回答数を集計
     */
    public function getStatusCounts(): array
    {
        $counts = $this->responseDetails()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'ok' => $counts['ok'] ?? 0,
            'maybe' => $counts['maybe'] ?? 0,
            'ng' => $counts['ng'] ?? 0,
        ];
    }
}
