<?php

namespace Database\Seeders;

use App\Models\InsurerSpecialtyCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsurerSpecialtyCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        InsurerSpecialtyCost::factory(50)->create();
    }
}
