<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignId('manager_id');
            $table->string('description');
            $table->text('link')->nullable();
            $table->foreignId('type_id');
            $table->date('posted_date');
            $table->date('due_date');
            $table->text('notes')->nullable();
            $table->integer('words')->nullable();
            $table->time('used_time')->nullable();
            $table->boolean('completed')->nullable();
            $table->dateTime('completed_date')->nullable();
            $table->boolean('priority')->default(false);
            $table->text('comments')->nullable();
            $table->time('time_spent')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
