<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventDate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * イベント機能のテスト
 */
class EventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * トップページが表示される
     */
    public function test_home_page_is_displayed(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home'));
    }

    /**
     * イベント作成ページが表示される
     */
    public function test_create_page_is_displayed(): void
    {
        $response = $this->get('/events/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Events/Create'));
    }

    /**
     * イベントを作成できる
     */
    public function test_can_create_event(): void
    {
        $response = $this->post('/events', [
            'title' => 'テストイベント',
            'description' => 'テストの説明',
            'dates' => [
                ['date' => '2025-01-15'],
                ['date' => '2025-01-16'],
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('events', [
            'title' => 'テストイベント',
            'description' => 'テストの説明',
        ]);

        $this->assertDatabaseCount('event_dates', 2);
    }

    /**
     * イベント名は必須
     */
    public function test_title_is_required(): void
    {
        $response = $this->post('/events', [
            'title' => '',
            'dates' => [
                ['date' => '2025-01-15'],
            ],
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * 候補日程は必須
     */
    public function test_dates_are_required(): void
    {
        $response = $this->post('/events', [
            'title' => 'テストイベント',
            'dates' => [],
        ]);

        $response->assertSessionHasErrors('dates');
    }

    /**
     * イベント詳細ページが表示される
     */
    public function test_show_page_is_displayed(): void
    {
        $event = Event::factory()->create();
        EventDate::factory()->count(3)->create(['event_id' => $event->id]);

        $response = $this->get("/e/{$event->uuid}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Events/Show')
            ->has('event')
            ->has('statistics')
        );
    }

    /**
     * 編集ページは正しいキーで表示される
     */
    public function test_edit_page_requires_valid_key(): void
    {
        $event = Event::factory()->create();

        // 正しいキー
        $response = $this->get("/e/{$event->uuid}/edit?key={$event->edit_key}");
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Events/Edit'));

        // 不正なキー
        $response = $this->get("/e/{$event->uuid}/edit?key=invalid_key");
        $response->assertStatus(403);
    }

    /**
     * イベントを更新できる
     */
    public function test_can_update_event(): void
    {
        $event = Event::factory()->create(['title' => '元のタイトル']);
        EventDate::factory()->create(['event_id' => $event->id, 'date' => '2025-01-15']);

        $response = $this->put("/e/{$event->uuid}?key={$event->edit_key}", [
            'title' => '更新後のタイトル',
            'dates' => [
                ['date' => '2025-01-20'],
            ],
        ]);

        $response->assertRedirect("/e/{$event->uuid}");

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => '更新後のタイトル',
        ]);
    }

    /**
     * イベントを削除できる
     */
    public function test_can_delete_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->delete("/e/{$event->uuid}?key={$event->edit_key}");

        $response->assertRedirect('/');

        $this->assertSoftDeleted('events', [
            'id' => $event->id,
        ]);
    }
}
