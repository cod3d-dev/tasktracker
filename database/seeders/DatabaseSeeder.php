<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Manager;
use App\Models\project;
use App\Models\task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'codelgado@gmail.com',
            'password' => Hash::make('m1p2ss'),
            'is_admin' => true
        ]);

        DB::table('types')->insert([
            ['name' => 'Translation'],
            ['name' => 'Proofreading'],
            ['name' => 'LSO']
        ]);

        Project::factory()
            ->count(5)
            ->create();

        Manager::factory()
            ->count(10)
            ->create();

        Task::factory()
            ->count(20)
            ->create();

    }
}
