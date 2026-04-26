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
        Schema::create('submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('applicant_name');
            $table->string('company_name');
            $table->date('submission_date');
            $table->unsignedBigInteger('total');
            $table->foreignUuid('status_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('workflow_id')->constrained()->cascadeOnDelete();
            $table->uuid('reapply_from_id')->nullable();
            $table->timestamps();
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->foreign('reapply_from_id')->references('id')->on('submissions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
