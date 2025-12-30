<?php

namespace Database\Factories;

use App\Models\EventDate;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * イベント日程ファクトリー
 */
class EventDateFactory extends Factory
{
    protected $model = EventDate::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'date' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'time_from' => fake()->optional(0.3)->time('H:i'),
            'time_to' => fake()->optional(0.3)->time('H:i'),
            'sort_order' => 0,
        ];
    }
}
