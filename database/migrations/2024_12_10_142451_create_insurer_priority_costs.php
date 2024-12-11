<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('insurer_priority_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurer_id')->constrained();
            $table->integer('priority');
            $table->decimal('percent_cost', 5, 2);
            $table->timestamps();
        });
    }
};
