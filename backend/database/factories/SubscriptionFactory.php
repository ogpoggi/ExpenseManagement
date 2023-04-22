<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'office_id' => Office::factory(),
            'price' => $this->faker->numberBetween(10.00, 20.00),
            'status' => Subscription::STATUS_ACTIVE,
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDays(5)->format('Y-m-d'),
        ];
    }

    public function cancelled(): Factory
    {
        return $this->state([
            'status' => Subscription::STATUS_CANCELLED,
        ]);
    }
}
