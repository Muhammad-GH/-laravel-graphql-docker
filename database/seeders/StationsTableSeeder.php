<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use App\Models\Station;
use Illuminate\Database\Seeder;

class StationsTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Schema::disableForeignKeyConstraints();
        Station::truncate();

        $latlngs = [
            [37.520278, -120.856803],
            [37.380729, -120.587784],
            [37.118374, -120.181018],
            [36.740741, -119.752265],
            [37.666888, -122.084615],
            [37.699493, -122.132712],
            [37.696233, -122.042014],
            [37.678772, -122.045490],
            [35.104655, -106.623915],
            [35.097914, -106.582832],
            [39.157419, -78.181247],
            [39.102024, -77.537721],
            [39.025252, -77.092478],
            [39.106287, -76.812139],
            [39.003911, -76.515310],
            [39.654011, -77.702626],
            [39.670926, -78.741568],
            [39.693897, -78.179193],
            [42.511336, -71.065590],
            [40.804194, -74.055867],
        ];

        $faker = \Faker\Factory::create();

        foreach (range(0, 19) as $i) {
            $j = array_rand($latlngs);
            Station::create([
                'company_id'     => rand(1,7),
                'name'    => $faker->city(),
                'lat'   => $latlngs[$j][0],
                'lng' => $latlngs[$j][1]
            ]);
            unset($latlngs[$j]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
