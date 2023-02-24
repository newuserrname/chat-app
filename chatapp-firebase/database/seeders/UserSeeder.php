<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->provider()->create();
        User::factory()->providertwo()->create();
        User::factory()->providerthree()->create();
        User::factory()->count(7)->seeker()->create();
    }
}
