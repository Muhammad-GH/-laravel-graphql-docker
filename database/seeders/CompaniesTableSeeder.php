<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Schema::disableForeignKeyConstraints();
        Company::truncate();

        $faker = \Faker\Factory::create();

        Company::create([
            'name'     => $faker->company(),
            'about'    => $faker->text(),
            'parent_id'   => 0,
        ]);

        Company::create([
            'name'     => $faker->company(),
            'about'    => $faker->text(),
            'parent_id'   => 0,
        ]);

        Company::create([
            'name'     => $faker->company(),
            'about'    => $faker->text(),
            'parent_id'   => 2,
        ]);

        Company::create([
            'name'     => $faker->company(),
            'about'    => $faker->text(),
            'parent_id'   => 0,
        ]);

        Company::create([
            'name'     => $faker->company(),
            'about'    => $faker->text(),
            'parent_id'   => 0,
        ]);

        Company::create([
            'name'     => $faker->company(),
            'about'    => $faker->text(),
            'parent_id'   => 3,
        ]);

        Company::create([
            'name'     => $faker->company(),
            'about'    => $faker->text(),
            'parent_id'   => 1,
        ]);
        Schema::enableForeignKeyConstraints();

    }
}
