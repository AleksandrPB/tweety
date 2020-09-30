<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tweet;
use Faker\Generator as Faker;

$factory->define(Tweet::class, function (Faker $faker) {
    return [
        //  Laravel will create new user and persist it if it necessary
        'user_id' => factory(App\User::class),
        'body' => $faker->sentence
    ];
});
