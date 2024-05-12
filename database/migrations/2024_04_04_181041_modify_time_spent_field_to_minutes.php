<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add a new temporary column for the converted time values in minutes
            $table->integer('time_spent_minutes')->default(0);
        });

        $tasks = DB::table('tasks')->select('id', 'time_spent')->get();

        foreach ($tasks as $task) {
            if ($task->time_spent) {
                list($hours, $minutes) = explode(':', $task->time_spent);
                // Assuming time_spent does not include seconds
                $totalMinutes = ((int) $hours * 60) + (int) $minutes;
                // Update the tasks with the converted time in minutes
                DB::table('tasks')
                    ->where('id', $task->id)
                    ->update(['time_spent_minutes' => $totalMinutes]);
            }
        }

        Schema::table('tasks', function (Blueprint $table) {
            // Drop the old time_spent column
            $table->dropColumn('time_spent');
            // Rename time_spent_minutes to time_spent
            $table->renameColumn('time_spent_minutes', 'time_spent');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Reverse the process if needed
            $table->time('time_spent_temp')->nullable();
            $table->renameColumn('time_spent', 'time_spent_minutes');
        });

        // Convert the minutes back to the time format HH:MM
        $tasks = DB::table('tasks')->select('id', 'time_spent_minutes')->get();

        foreach ($tasks as $task) {
            $hours = intdiv($task->time_spent_minutes, 60);
            $minutes = $task->time_spent_minutes % 60;
            $time_spent = sprintf('%02d:%02d', $hours, $minutes);
            // Update the tasks with the converted time
            DB::table('tasks')
                ->where('id', $task->id)
                ->update(['time_spent_temp' => $time_spent]);
        }

        Schema::table('tasks', function (Blueprint $table) {
            // Drop the time_spent_minutes column and rename the temporary column back
            $table->dropColumn('time_spent_minutes');
            $table->renameColumn('time_spent_temp', 'time_spent');
        });
    }
};
