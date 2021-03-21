<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['NEW','OLD']);
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('remarks')->nullable();
            $table->string('mobile')->nullable();
            $table->string('reservation_code')->nullable();
            $table->string('date_reserve')->nullable();
            $table->enum('status',['RESERVED','ENROLLED','CANCELLED']);

            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();

            $table->foreign('student_id')->nullable()->references('id')->on('students');
            $table->foreign('department_id')->nullable()->references('id')->on('departments');
            $table->foreign('grade_id')->nullable()->references('id')->on('grades');
            $table->foreign('school_year_id')->nullable()->references('id')->on('school_years');
            $table->foreign('section_id')->nullable()->references('id')->on('sections');

            $table->string('reservation_fee')->nullable();
            $table->string('reservation_payer')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
