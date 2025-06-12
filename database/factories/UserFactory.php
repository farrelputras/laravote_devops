<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'remember_token' => Str::random(10),
        'nik' => $faker->unique()->numerify('32##############'), // 16 digit
        'phone' => $faker->numerify('08##########'), // 12 digit
        'address' => $faker->address,
        'roles' => json_encode(["VOTER"]),
        'status' => 'BELUM',
        'is_eligible' => true,
    ];
});
