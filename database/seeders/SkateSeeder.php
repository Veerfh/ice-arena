<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skate;
use Carbon\Carbon;

class SkateSeeder extends Seeder
{
    public function run()
    {
        $skates = [
            [
                'brand' => 'Bauer',
                'model' => 'Vapor X3.7',
                'size' => 42,
                'quantity' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'Bauer',
                'model' => 'Supreme M5 Pro',
                'size' => 43,
                'quantity' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'CCM',
                'model' => 'Ribcor 90K',
                'size' => 41,
                'quantity' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'CCM',
                'model' => 'JetSpeed FT4 Pro',
                'size' => 44,
                'quantity' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'Graf',
                'model' => 'Cobra 709',
                'size' => 40,
                'quantity' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'Graf',
                'model' => 'Supra 5035',
                'size' => 45,
                'quantity' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'Bauer',
                'model' => 'Nexus N2700',
                'size' => 38,
                'quantity' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'CCM',
                'model' => 'Tacks AS-V Pro',
                'size' => 42,
                'quantity' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'True',
                'model' => 'TF9',
                'size' => 39,
                'quantity' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'True',
                'model' => 'Catalyst 7',
                'size' => 43,
                'quantity' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'Risport',
                'model' => 'RF3 Pro',
                'size' => 37,
                'quantity' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'EDEA',
                'model' => 'Overture',
                'size' => 36,
                'quantity' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'Jackson',
                'model' => 'Artiste',
                'size' => 38,
                'quantity' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'Bauer',
                'model' => 'Whisper',
                'size' => 44,
                'quantity' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'brand' => 'CCM',
                'model' => 'Super Tacks X',
                'size' => 41,
                'quantity' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($skates as $skate) {
            Skate::create($skate);
        }
    }
}