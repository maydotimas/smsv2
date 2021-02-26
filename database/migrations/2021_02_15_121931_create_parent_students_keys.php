<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentStudentsKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('father_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->unsignedBigInteger('guardian_id')->nullable();
            $table->unsignedBigInteger('emergency_contact')->nullable();

            $table->foreign('father_id')->nullable()->references('id')->on('student_parents');
            $table->foreign('mother_id')->nullable()->references('id')->on('student_parents');
            $table->foreign('guardian_id')->nullable()->references('id')->on('student_parents');
            $table->foreign('emergency_contact')->nullable()->references('id')->on('student_parents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
