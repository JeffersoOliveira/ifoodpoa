<?php

namespace Database\Seeders;

use App\Models\Bike;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bikes = [];
        $i = 5;
        while ($i < 50){
            $nPatrimonio = 600;
            $bike = [
                "patrimony" => "K". $nPatrimonio+$i,
                "status" => "",
                "series" => 888888888+$i
            ];

            $i++;
            $bikes[] = $bike;
        }
//            dd($bikes);

        foreach ($bikes as $bike){

            Bike::updateOrCreate(
                [
                    'patrimony' => $bike['patrimony']
                ],[
                    'status' => $bike['status'],
                    'series' => $bike['series']

                ]);
        }
    }
}
