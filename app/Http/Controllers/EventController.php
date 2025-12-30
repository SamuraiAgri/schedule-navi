<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventStatisticsService;
use App\Services\CsvExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * イベントコントローラー
 * イベントの作成・表示・編集・削除を管理
 */
class EventController extends Controller
{
    public function __construct(
        private readonly EventStatisticsService $statisticsService,
        private readonly CsvExportService $csvExportService,
    ) {}

    /**
     * トップページを表示
     */
    public function index(): InertiaResponse
    {
        return Inertia::render('Home');
    }

    /**
     * イベント作成画面を表示
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('Events/Create');
    }

    /**
     * イベントを保存
     */
    public function store(StoreEventRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $event = DB::transaction(function () use ($validated) {
            // イベント作成
            $event = Event::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'notify_email' => $validated['notify_email'] ?? null,
                'is_time_required' => $validated['is_time_required'] ?? false,
                'is_anonymous_allowed' => $validated['is_anonymous_allowed'] ?? true,
            ]);

            // 候補日程を作成
            $dates = collect($validated['dates'])->map(function ($date, $index) {
                return [
                    'date' => $date['date'],
                    'time_from' => $date['time_from'] ?? null,
                    'time_to' => $date['time_to'] ?? null,
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            $event->dates()->createMany($dates);

            return $event;
        });

        return redirect()->route('events.created', [
            'uuid' => $event->uuid,
            'key' => $event->edit_key,
        ]);
    }

    /**
     * イベント作成完了画面を表示
     */
    public function created(Request $request, string $uuid): InertiaResponse
    {
        $event = $this->findEventByUuid($uuid);
        $key = $request->query('key');

        // 編集キーが正しい場合のみ表示
        if (!$event->verifyEditKey($key)) {
            abort(403);
        }

        return Inertia::render('Events/Created', [
            'event' => [
                'uuid' => $event->uuid,
                'title' => $event->title,
                'shareUrl' => $event->share_url,
                'editUrl' => $event->edit_url,
            ],
        ]);
    }

    /**
     * イベント詳細画面を表示（回答・集計ページ）
     */
    public function show(string $uuid): InertiaResponse
    {
        $event = $this->findEventByUuid($uuid);
        $event->load(['dates', 'responses.details']);
        
        // 閲覧数をインクリメント
        $event->incrementViewCount();

        // 統計情報を計算
        $statistics = $this->statisticsService->calculate($event);

        return Inertia::render('Events/Show', [
            'event' => [
                'uuid' => $event->uuid,
                'title' => $event->title,
                'description' => $event->description,
                'isTimeRequired' => $event->is_time_required,
                'isAnonymousAllowed' => $event->is_anonymous_allowed,
                'isActive' => $event->is_active,
                'deadlineAt' => $event->deadline_at?->format('Y-m-d H:i'),
                'viewCount' => $event->view_count,
                'dates' => $event->dates->map(fn ($date) => [
                    'id' => $date->id,
                    'date' => $date->date->format('Y-m-d'),
                    'formattedDate' => $date->formatted_date,
                    'formattedTime' => $date->formatted_time,
                    'fullLabel' => $date->full_label,
                ]),
                'responses' => $event->responses->map(fn ($response) => [
                    'id' => $response->id,
                    'participantName' => $response->display_name,
                    'comment' => $response->comment,
                    'createdAt' => $response->created_at->format('Y/m/d H:i'),
                    'details' => $response->getDetailsMap(),
                ]),
            ],
            'statistics' => $statistics,
        ]);
    }

    /**
     * イベント編集画面を表示
     */
    public function edit(Request $request, string $uuid): InertiaResponse
    {
        $event = $this->findEventByUuid($uuid);
        $key = $request->query('key');

        if (!$event->verifyEditKey($key)) {
            abort(403);
        }

        $event->load('dates');

        return Inertia::render('Events/Edit', [
            'event' => [
                'uuid' => $event->uuid,
                'editKey' => $event->edit_key,
                'title' => $event->title,
                'description' => $event->description,
                'notifyEmail' => $event->notify_email,
                'isTimeRequired' => $event->is_time_required,
                'isAnonymousAllowed' => $event->is_anonymous_allowed,
                'deadlineAt' => $event->deadline_at?->format('Y-m-d\TH:i'),
                'dates' => $event->dates->map(fn ($date) => [
                    'id' => $date->id,
                    'date' => $date->date->format('Y-m-d'),
                    'time_from' => $date->time_from?->format('H:i'),
                    'time_to' => $date->time_to?->format('H:i'),
                ]),
            ],
        ]);
    }

    /**
     * イベントを更新
     */
    public function update(UpdateEventRequest $request, string $uuid): \Illuminate\Http\RedirectResponse
    {
        $event = $this->findEventByUuid($uuid);
        $key = $request->query('key');

        if (!$event->verifyEditKey($key)) {
            abort(403);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($event, $validated) {
            $event->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'notify_email' => $validated['notify_email'] ?? null,
                'is_time_required' => $validated['is_time_required'] ?? false,
                'is_anonymous_allowed' => $validated['is_anonymous_allowed'] ?? true,
                'deadline_at' => $validated['deadline_at'] ?? null,
            ]);

            // 既存の日程を削除して再作成
            $event->dates()->delete();
            
            $dates = collect($validated['dates'])->map(function ($date, $index) {
                return [
                    'date' => $date['date'],
                    'time_from' => $date['time_from'] ?? null,
                    'time_to' => $date['time_to'] ?? null,
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            $event->dates()->createMany($dates);
        });

        return redirect()->route('events.show', $uuid)
            ->with('success', 'イベントを更新しました');
    }

    /**
     * イベントを削除（論理削除）
     */
    public function destroy(Request $request, string $uuid): \Illuminate\Http\RedirectResponse
    {
        $event = $this->findEventByUuid($uuid);
        $key = $request->query('key');

        if (!$event->verifyEditKey($key)) {
            abort(403);
        }

        $event->update(['status' => 'deleted']);
        $event->delete();

        return redirect()->route('home')
            ->with('success', 'イベントを削除しました');
    }

    /**
     * CSV出力
     */
    public function exportCsv(Request $request, string $uuid): StreamedResponse
    {
        $event = $this->findEventByUuid($uuid);
        $key = $request->query('key');

        if (!$event->verifyEditKey($key)) {
            abort(403);
        }

        $event->load(['dates', 'responses.details']);

        return $this->csvExportService->export($event);
    }

    /**
     * UUIDでイベントを検索
     */
    private function findEventByUuid(string $uuid): Event
    {
        return Event::findByUuidOrFail($uuid);
    }
}
