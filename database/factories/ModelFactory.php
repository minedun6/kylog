<?php

use App\Models\Company\Company;
use App\Models\Product\Product;
use Faker\Generator;
use App\Models\Access\Role\Role;
use App\Models\Access\User\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'confirmation_code' => md5(uniqid(mt_rand(), true)),
    ];
});

$factory->state(User::class, 'active', function () {
    return [
        'status' => 1,
    ];
});

$factory->state(User::class, 'inactive', function () {
    return [
        'status' => 0,
    ];
});

$factory->state(User::class, 'confirmed', function () {
    return [
        'confirmed' => 1,
    ];
});

$factory->state(User::class, 'unconfirmed', function () {
    return [
        'confirmed' => 0,
    ];
});

/*
 * Roles
 */
$factory->define(Role::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'all' => 0,
        'sort' => $faker->numberBetween(1, 100),
    ];
});

$factory->state(Role::class, 'admin', function () {
    return [
        'all' => 1,
    ];
});

/*
 * Supplier
 */
$factory->define(Company::class, function (Generator $faker) {
    return [
        'name' => $faker->company,
        'trn' => str_random(10),
        'customs' => str_random(10),
        'address' => $faker->address,
        'comment' => null,
        'logo' => null,
        'type' => $faker->randomElement(['1', '2'])
    ];
});

/*
 * Products
 */
$factory->define(Product::class, function (Generator $faker) {
    return [
        'reference' => str_random(10),
        'supplier_reference' => str_random(10),
        'designation' => $faker->word,
        'value' => $faker->numberBetween(1000, 5000),
        'net_weight' => $faker->numberBetween(100, 800),
        'brut_weight' => $faker->numberBetween(100, 800),
        'piece' => 1,
        'unit' => null,
        'sap' => str_random(12),
        'supplier_id' => factory(Company::class)->create(['type' => '1'])->id,
    ];
});