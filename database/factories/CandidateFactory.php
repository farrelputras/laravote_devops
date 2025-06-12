<?php

use App\Candidate;
use Faker\Generator as Faker;

$factory->define(Candidate::class, function (Faker $faker) {
    return [
        'nama_ketua' => $faker->name . ' ' . $faker->name,
        'nama_wakil' => $faker->name . ' ' . $faker->name,
        'visi' => $faker->sentence(10),
        'misi' => $faker->sentence(15),
        'program_kerja' => $faker->paragraph(3),
        'photo_paslon' => 'paslon/default.jpg', // asumsi default
    ];
});
