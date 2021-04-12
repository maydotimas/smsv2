<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollment_payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();

            $table->string('payment_no')->nullable();
            $table->string('payment_month')->nullable();
            $table->string('payment_tf_amount')->nullable();
            $table->string('payment_misc_amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('payment_due')->nullable();
            $table->string('interest')->nullable();

            $table->string('accomplished_date')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status',['UNPAID','PAID']);

            $table->foreign('enrollment_id')->nullable()->references('id')->on('enrollments');
            $table->foreign('school_year_id')->nullable()->references('id')->on('school_years');
            $table->foreign('student_id')->nullable()->references('id')->on('students');

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
        Schema::dropIfExists('enrollment_payments');
    }
}
