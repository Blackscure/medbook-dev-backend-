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
        Schema::create('tbl_patient', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_of_birth');
            $table->unsignedBigInteger('gender_id'); // Foreign key reference to tbl_gender
            $table->unsignedBigInteger('type_of_service'); // Foreign key reference to tbl_service
            $table->text('general_comments')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('gender_id')->references('id')->on('tbl_gender');
            $table->foreign('type_of_service')->references('id')->on('tbl_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_patient');
    }
};
