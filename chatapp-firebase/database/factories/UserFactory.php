<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('Password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function provider()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Thanh Trường',
                'email' => 'truong@example.com',
                'role' => 2,
            ];
        });
    }

    public function providertwo()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Provider Two',
                'email' => 'providertwo@example.com',
                'role' => 2,
            ];
        });
    }

    public function providerthree()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Provider Three',
                'email' => 'providerthree@example.com',
                'role' => 2,
            ];
        });
    }

    public function seeker()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'role' => 3,
            ];
        });
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
