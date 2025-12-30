<?php

namespace App\Services;

use App\Models\Event;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CSV出力サービス
 * イベントの回答データをCSV形式で出力
 */
class CsvExportService
{
    /**
     * イベントデータをCSVとして出力
     */
    public function export(Event $event): StreamedResponse
    {
        $filename = $this->generateFilename($event);

        return response()->streamDownload(function () use ($event) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM（Excel対応）
            fwrite($handle, "\xEF\xBB\xBF");

            // ヘッダー行を出力
            $headers = $this->buildHeaders($event);
            fputcsv($handle, $headers);

            // データ行を出力
            foreach ($event->responses as $response) {
                $row = $this->buildDataRow($event, $response);
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * ファイル名を生成
     */
    private function generateFilename(Event $event): string
    {
        $title = preg_replace('/[^\p{L}\p{N}]/u', '_', $event->title);
        $date = now()->format('Ymd');
        
        return "schedule-navi_{$title}_{$date}.csv";
    }

    /**
     * ヘッダー行を構築
     */
    private function buildHeaders(Event $event): array
    {
        $headers = ['参加者名', 'コメント', '回答日時'];
        
        foreach ($event->dates as $date) {
            $headers[] = $date->full_label;
        }
        
        return $headers;
    }

    /**
     * データ行を構築
     */
    private function buildDataRow(Event $event, $response): array
    {
        $statusLabels = [
            'ok' => '○',
            'ng' => '×',
            'maybe' => '△',
        ];

        $row = [
            $response->display_name,
            $response->comment ?? '',
            $response->created_at->format('Y/m/d H:i'),
        ];

        $detailsMap = $response->getDetailsMap();

        foreach ($event->dates as $date) {
            $status = $detailsMap[$date->id] ?? null;
            $row[] = $status ? ($statusLabels[$status] ?? '-') : '-';
        }

        return $row;
    }
}
