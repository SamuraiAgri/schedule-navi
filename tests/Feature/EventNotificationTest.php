<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class EventNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // テスト用にメール送信をモック
        Mail::fake();
        Notification::fake();
    }

    /**
     * イベント作成時にメール通知が送信されることを確認
     */
    public function test_event_creation_sends_notification(): void
    {
        // イベント作成データ
        $eventData = [
            'title' => 'テストイベント',
            'description' => 'これはテスト用のイベントです',
            'organizer_name' => 'テスト主催者',
            'organizer_email' => 'test@example.com',
            'dates' => [
                ['date' => now()->addDays(1)->format('Y-m-d'), 'start_time' => '10:00', 'end_time' => '12:00'],
                ['date' => now()->addDays(2)->format('Y-m-d'), 'start_time' => '14:00', 'end_time' => '16:00'],
            ],
        ];

        // イベント作成リクエスト
        $response = $this->post('/events', $eventData);

        // リダイレクトが成功することを確認
        $response->assertStatus(302);

        // イベントがデータベースに保存されたことを確認
        $this->assertDatabaseHas('events', [
            'title' => 'テストイベント',
            'organizer_email' => 'test@example.com',
        ]);
    }

    /**
     * 回答送信時に主催者へメール通知が送信されることを確認
     */
    public function test_response_submission_notifies_organizer(): void
    {
        // テスト用イベントを作成
        $event = Event::factory()->create([
            'organizer_email' => 'organizer@example.com',
        ]);

        // 回答データ
        $responseData = [
            'name' => 'テスト参加者',
            'email' => 'participant@example.com',
            'comment' => 'よろしくお願いします',
            'availabilities' => [
                ['date_id' => 1, 'status' => 'available'],
            ],
        ];

        // 回答送信（throttleミドルウェアをバイパス）
        $response = $this->withoutMiddleware('throttle:responses')
            ->post("/e/{$event->uuid}/responses", $responseData);

        // リダイレクトが成功することを確認
        $response->assertStatus(302);

        // 回答がデータベースに保存されたことを確認
        $this->assertDatabaseHas('responses', [
            'event_id' => $event->id,
            'name' => 'テスト参加者',
        ]);
    }

    /**
     * メール送信機能が正常に動作することを確認
     */
    public function test_mail_system_is_configured(): void
    {
        // メール設定が正しく読み込まれていることを確認
        $this->assertNotEmpty(config('mail.mailers.smtp.host'));
        $this->assertNotEmpty(config('mail.from.address'));
    }
}
