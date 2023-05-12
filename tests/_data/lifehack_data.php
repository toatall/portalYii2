<?php
namespace tests\_data;

use \Faker;

$faker = Faker\Factory::create();

return [
    1 => [
        'org_code' => '8600',
        'tags' => '#tag',
        'title' => $faker->slug(),
        'text' => $faker->text(1000),
        'author_name' => 'Author 1',
        'date_create' => \Yii::$app->formatter->asDatetime('now'),
        'date_update' => \Yii::$app->formatter->asDatetime('now'),
        'username' => 'admin',
    ],
    2 => [
        'org_code' => '8601',
        'tags' => '#tag',
        'title' => $faker->slug(),
        'text' => $faker->text(1000),
        'author_name' => 'Author 2',
        'date_create' => \Yii::$app->formatter->asDatetime('now'),
        'date_update' => \Yii::$app->formatter->asDatetime('now'),
        'username' => 'user-ifns',
    ],
    3 => [
        'org_code' => '8600',
        'tags' => '#new/#excel',
        'title' => $faker->slug(),
        'text' => $faker->text(1000),
        'author_name' => 'Author 4',
        'date_create' => \Yii::$app->formatter->asDatetime('now'),
        'date_update' => \Yii::$app->formatter->asDatetime('now'),
        'username' => 'admin',
    ],
    4 => [
        'org_code' => '8600',
        'tags' => '#excel',
        'title' => $faker->slug(),
        'text' => $faker->text(1000),
        'author_name' => 'Author 2',
        'date_create' => \Yii::$app->formatter->asDatetime('now'),
        'date_update' => \Yii::$app->formatter->asDatetime('now'),
        'username' => 'admin',
    ],
    5 => [
        'org_code' => '8602',
        'tags' => '#word/#excel',
        'title' => $faker->slug(),
        'text' => $faker->text(1000),
        'author_name' => 'Author 1',
        'date_create' => \Yii::$app->formatter->asDatetime('now'),
        'date_update' => \Yii::$app->formatter->asDatetime('now'),
        'username' => 'user',
    ],
];