<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('students');
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lrn')->unique()->nullable();
            $table->string('student_no')->nullable();
            $table->string('student_type')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('nickname')->nullable();
            $table->string('handedness');
            $table->string('religion')->nullable();
            $table->string('address')->nullable();
            $table->string('street')->nullable();
            $table->string('brgy')->nullable();
            $table->string('town')->nullable();
            $table->string('province')->nullable();
            $table->string('gender');
            $table->string('birthdate');
            $table->string('email');
            $table->string('mobile');
            $table->string('avatar')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
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
        Schema::dropIfExists('students');
    }
}
