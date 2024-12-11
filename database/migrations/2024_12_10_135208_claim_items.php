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
        //
        Schema::create('claim_items', function (Blueprint $table) { 
            $table->id(); 
            $table->foreignId('claim_id')->constrained();
            $table->string('name');
            $table->decimal('unit_price', 18, 2);
            $table->integer('quantity');
            $table->decimal('sub_total', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
