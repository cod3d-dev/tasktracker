<?php

namespace Database\Factories;

use App\Models\Mail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MailFactory extends Factory
{
    protected $model = Mail::class;

    public function definition(): array
    {
        return [
            'message_id' => $this->faker->word(),
            'subject' => $this->faker->word(),
            'body' => $this->faker->word(),
            'from' => $this->faker->word(),
            'processed' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
