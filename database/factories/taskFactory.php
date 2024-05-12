<?php

namespace Database\Factories;

use App\Models\task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class taskFactory extends Factory
{
    protected $model = task::class;

    public function definition()
    {
        $projectIDs = DB::table('projects')->pluck('id');
        $managerIDs = DB::table('projects')->pluck('id');
        $typeIDs = DB::table('types')->pluck('id');
        $startingDate = $this->faker->dateTimeBetween('-30 days','today' );
        $dueDate = $this->faker->dateTimeBetween($startingDate, '+15 days');
        $taskStatus = rand(0,2);

        if($taskStatus == 2){
            $completedDate = $this->faker->dateTimeBetween($startingDate, $dueDate);
        } elseif ($taskStatus == 1){
            $completedDate = null;
        } else {
            $completedDate = null;
        }


        return [
            'project_id' => $this->faker->randomElement($projectIDs),
            'manager_id' => $this->faker->randomElement($managerIDs),
            'description' => $this->faker->sentence(5),
            'link' => $this->faker->url(),
            'type_id' => $this->faker->randomElement($typeIDs),
            'posted_date' => $startingDate,
            'due_date' => $dueDate,
            'notes' => $this->faker->word(10),
            'words' => $this->faker->randomNumber(4),
            'used_time' => $this->faker->time(),
            'completed' => $this->faker->boolean(),
            'time_spent' => $this->faker->randomNumber( 2),
            'completed_date' => $completedDate,
            'priority' => $this->faker->boolean(),
            'comments' => $this->faker->sentence(),
            'status' => $taskStatus,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}


