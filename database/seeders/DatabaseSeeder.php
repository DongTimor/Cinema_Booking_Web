<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Permission::factory(5)->create();
        \App\Models\Role::factory(5)->create();
        \App\Models\User::factory(5)->create();
        \App\Models\Theater::factory(1)->create();
        \App\Models\Customer::factory(10)->create();
        \App\Models\Movie::factory(15)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Image::factory(30)->create();
        \App\Models\Auditorium::factory(25)->create();
        \App\Models\Seat::factory(50)->create();
        \App\Models\Showtime::factory(20)->create();
        \App\Models\Ticket::factory(100)->create();




    }
}
