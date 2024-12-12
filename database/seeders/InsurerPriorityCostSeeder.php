<?php

namespace Database\Seeders;

use App\Models\InsurerPriorityCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsurerPriorityCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        InsurerPriorityCost::factory(50)->create();
    }
}
