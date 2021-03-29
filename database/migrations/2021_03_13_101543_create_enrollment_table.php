<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['NEW','OLD']);
            $table->string('remarks')->nullable();
            $table->string('date_enrolled')->nullable();
            $table->enum('status',['ENROLLED','CANCELLED']);
            $table->enum('discount',['NONE','SIBLING','SCHOLAR']);
            $table->string('percent')->nullable();

            /* tuition details */
            $table->enum('payment_mode',['ANNUAL','SEMESTRAL','QUARTERLY','MONTHLY']);
            $table->string('reservation_fee')->nullable();
            $table->string('months')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('total_tf')->nullable();
            $table->string('total_misc')->nullable();
            $table->string('monthly_tuition_fee')->nullable();
            $table->string('monthly_misc_fee')->nullable();
            $table->string('enrollment_tuition_fee')->nullable();
            $table->string('enrollment_misc_fee')->nullable();

            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();

            $table->string('enrolled_by')->nullable();
            $table->unsignedBigInteger('processor_id')->nullable();

            $table->foreign('school_year_id')->nullable()->references('id')->on('school_years');
            $table->foreign('reservation_id')->nullable()->references('id')->on('reservations');
            $table->foreign('student_id')->nullable()->references('id')->on('students');
            $table->foreign('department_id')->nullable()->references('id')->on('departments');
            $table->foreign('grade_id')->nullable()->references('id')->on('grades');
            $table->foreign('section_id')->nullable()->references('id')->on('sections');
            $table->foreign('processor_id')->nullable()->references('id')->on('users');

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
        Schema::dropIfExists('enrollments');
    }
}
