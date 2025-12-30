<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventDate;
use App\Models\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 回答機能のテスト
 */
class ResponseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 回答を送信できる
     */
    public function test_can_submit_response(): void
    {
        $event = Event::factory()->create();
        $dates = EventDate::factory()->count(3)->create(['event_id' => $event->id]);

        $response = $this->post("/e/{$event->uuid}/responses", [
            'participant_name' => '山田太郎',
            'comment' => 'よろしくお願いします',
            'answers' => [
                $dates[0]->id => 'ok',
                $dates[1]->id => 'maybe',
                $dates[2]->id => 'ng',
            ],
        ]);

        $response->assertRedirect("/e/{$event->uuid}");

        $this->assertDatabaseHas('responses', [
            'event_id' => $event->id,
            'participant_name' => '山田太郎',
            'comment' => 'よろしくお願いします',
        ]);

        $this->assertDatabaseCount('response_details', 3);
    }

    /**
     * 参加者名は必須
     */
    public function test_participant_name_is_required(): void
    {
        $event = Event::factory()->create();
        $date = EventDate::factory()->create(['event_id' => $event->id]);

        $response = $this->post("/e/{$event->uuid}/responses", [
            'participant_name' => '',
            'answers' => [
                $date->id => 'ok',
            ],
        ]);

        $response->assertSessionHasErrors('participant_name');
    }

    /**
     * 少なくとも1つの回答が必要
     */
    public function test_at_least_one_answer_is_required(): void
    {
        $event = Event::factory()->create();
        EventDate::factory()->create(['event_id' => $event->id]);

        $response = $this->post("/e/{$event->uuid}/responses", [
            'participant_name' => '山田太郎',
            'answers' => [],
        ]);

        $response->assertSessionHasErrors('answers');
    }

    /**
     * 匿名回答ができる
     */
    public function test_can_submit_anonymous_response(): void
    {
        $event = Event::factory()->create(['is_anonymous_allowed' => true]);
        $date = EventDate::factory()->create(['event_id' => $event->id]);

        $response = $this->post("/e/{$event->uuid}/responses", [
            'participant_name' => '山田太郎',
            'is_anonymous' => true,
            'answers' => [
                $date->id => 'ok',
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('responses', [
            'event_id' => $event->id,
            'participant_name' => '山田太郎',
            'is_anonymous' => true,
        ]);
    }

    /**
     * 非アクティブなイベントには回答できない
     */
    public function test_cannot_submit_to_inactive_event(): void
    {
        $event = Event::factory()->create([
            'status' => 'closed',
        ]);
        $date = EventDate::factory()->create(['event_id' => $event->id]);

        $response = $this->post("/e/{$event->uuid}/responses", [
            'participant_name' => '山田太郎',
            'answers' => [
                $date->id => 'ok',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
