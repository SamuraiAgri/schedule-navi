<?php

namespace Database\Factories;

use App\Models\Response;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * 回答ファクトリー
 */
class ResponseFactory extends Factory
{
    protected $model = Response::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'participant_name' => fake()->name(),
            'participant_email' => fake()->optional()->safeEmail(),
            'comment' => fake()->optional()->sentence(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'is_anonymous' => fake()->boolean(10),
        ];
    }

    /**
     * 匿名回答
     */
    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_anonymous' => true,
        ]);
    }
}
