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
        Schema::table('batched_claims', function (Blueprint $table) {
            $table->decimal('insurer_specialty_percent_cost', 5, 2)->nullable();
            $table->integer('priority_level')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('encounter_date')->nullable();
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
