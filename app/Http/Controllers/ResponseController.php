<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResponseRequest;
use App\Jobs\SendResponseNotification;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

/**
 * 回答コントローラー
 * イベントへの出欠回答を管理
 */
class ResponseController extends Controller
{
    /**
     * 回答を保存
     */
    public function store(StoreResponseRequest $request, string $uuid): RedirectResponse
    {
        $event = Event::findByUuidOrFail($uuid);

        // イベントがアクティブか確認
        if (!$event->is_active) {
            return back()->with('error', 'このイベントは回答を受け付けていません');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($event, $validated, $request) {
            // 回答を作成
            $response = $event->responses()->create([
                'participant_name' => $validated['participant_name'],
                'participant_email' => $validated['participant_email'] ?? null,
                'comment' => $validated['comment'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                'is_anonymous' => $validated['is_anonymous'] ?? false,
            ]);

            // 回答詳細を作成
            $details = collect($validated['answers'])->map(function ($status, $dateId) {
                return [
                    'event_date_id' => $dateId,
                    'status' => $status,
                ];
            })->filter(function ($detail) {
                return in_array($detail['status'], ['ok', 'ng', 'maybe']);
            })->values()->toArray();

            $response->details()->createMany($details);

            // メール通知をキューに追加
            if ($event->notify_email) {
                SendResponseNotification::dispatch($event, $response);
            }
        });

        return redirect()->route('events.show', $uuid)
            ->with('success', '回答を送信しました！');
    }
}
