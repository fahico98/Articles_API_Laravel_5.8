<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
   return [
      "title" => $faker->realText(30),
      "body" => $faker->realText(300)
   ];
});
