<?php

use App\Enums\ClaimPriorityEnum;
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
        Schema::table('claims', function (Blueprint $table) {
            //
            $table->foreignId('insurer_id')->nullable()->constrained();
            $table->foreignId('specialty_id')->nullable()->constrained();
            $table->foreignId('provider_id')->nullable()->constrained();
            $table->integer('priority_level')->default(ClaimPriorityEnum::ONE->value);
            $table->date('encounter_date');
            $table->date('submission_date');
            $table->decimal('total_amount', 25, 2);
            $table->timestamp('processed_at')->nullable();
            $table->dropTimestamps();
        });
    }
};
