<?php

namespace Database\Factories;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ManagerFactory extends Factory
{
    protected $model = Manager::class;

    public function definition(): array
    {
        $projectIDs = DB::table('projects')->pluck('id');

        return [
            'name' => $this->faker->name(),
            'project_id' => $this->faker->randomElement( $projectIDs),
            'email' => $this->faker->unique()->safeEmail(),
            'slack' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
