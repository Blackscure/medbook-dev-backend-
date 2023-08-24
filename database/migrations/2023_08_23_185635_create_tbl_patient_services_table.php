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
        Schema::create('tbl_patient_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id'); // Add patient_id column
            $table->unsignedBigInteger('service_id'); // Add service_id column
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_patient_services');
    }
};

