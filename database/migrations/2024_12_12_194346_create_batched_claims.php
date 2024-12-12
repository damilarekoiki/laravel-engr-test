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
        Schema::create('batched_claims', function (Blueprint $table) {
            $table->id();
            $table->string('indentifier');
            $table->foreignId('claim_id')->constrained();
            $table->date('date');
            $table->decimal('processing_cost', 25, 2);
            $table->foreignId('insurer_id')->constrained();
            $table->string('insurer_email');
            $table->decimal('total_amount', 25, 2);
            $table->timestamps();
        });
    }
};
