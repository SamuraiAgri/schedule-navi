<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * イベントファクトリー
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'edit_key' => hash('sha256', Str::random(32)),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'notify_email' => fake()->optional()->safeEmail(),
            'is_time_required' => fake()->boolean(20),
            'is_anonymous_allowed' => fake()->boolean(80),
            'max_participants' => fake()->optional()->numberBetween(5, 50),
            'deadline_at' => fake()->optional()->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'active',
            'view_count' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * クローズ状態
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }

    /**
     * 期限切れ
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'deadline_at' => now()->subDay(),
        ]);
    }
}
