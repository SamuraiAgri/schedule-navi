<?php

namespace App\Services;

use App\Models\Event;
use App\Models\ResponseDetail;

/**
 * イベント統計サービス
 * 回答の集計・分析を行う
 */
class EventStatisticsService
{
    /**
     * イベントの統計情報を計算
     */
    public function calculate(Event $event): array
    {
        $event->loadMissing(['dates', 'responses.details']);

        $dateStats = $this->calculateDateStats($event);
        $summary = $this->calculateSummary($event, $dateStats);

        return [
            'dateStats' => $dateStats,
            'summary' => $summary,
            'totalResponses' => $event->responses->count(),
        ];
    }

    /**
     * 各日程の集計を計算
     */
    private function calculateDateStats(Event $event): array
    {
        $stats = [];

        foreach ($event->dates as $date) {
            $counts = [
                'ok' => 0,
                'maybe' => 0,
                'ng' => 0,
            ];

            foreach ($event->responses as $response) {
                $detail = $response->details->firstWhere('event_date_id', $date->id);
                if ($detail) {
                    $counts[$detail->status]++;
                }
            }

            $total = $counts['ok'] + $counts['maybe'] + $counts['ng'];
            $rate = $total > 0 ? round(($counts['ok'] / $total) * 100) : 0;

            $stats[$date->id] = [
                'dateId' => $date->id,
                'ok' => $counts['ok'],
                'maybe' => $counts['maybe'],
                'ng' => $counts['ng'],
                'total' => $total,
                'rate' => $rate,
            ];
        }

        return $stats;
    }

    /**
     * サマリー情報を計算
     */
    private function calculateSummary(Event $event, array $dateStats): array
    {
        if (empty($dateStats)) {
            return [
                'bestDateId' => null,
                'bestDateLabel' => null,
                'bestDateOkCount' => 0,
            ];
        }

        // 最も○が多い日程を特定
        $bestDateId = null;
        $maxOk = -1;

        foreach ($dateStats as $dateId => $stat) {
            if ($stat['ok'] > $maxOk) {
                $maxOk = $stat['ok'];
                $bestDateId = $dateId;
            }
        }

        $bestDate = $event->dates->firstWhere('id', $bestDateId);

        return [
            'bestDateId' => $bestDateId,
            'bestDateLabel' => $bestDate?->full_label,
            'bestDateOkCount' => $maxOk,
        ];
    }
}
