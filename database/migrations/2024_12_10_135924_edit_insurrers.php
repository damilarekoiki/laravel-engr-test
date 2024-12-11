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
        Schema::table('insurers', function (Blueprint $table) {
            $table->integer('daily_processing_capacity');
            $table->integer('minimum_batch_size');
            $table->integer('maximum_batch_size');
            $table->string('batching_date_type');
        });
    }
};
