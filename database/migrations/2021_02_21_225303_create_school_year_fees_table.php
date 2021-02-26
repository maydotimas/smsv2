<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolYearFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_year_fees', function (Blueprint $table) {
            $table->id();
            /*payment detail*/
            $table->enum('type',['ANNUAL','SEMESTRAL','QUARTERLY','MONTHLY']);
            $table->unsignedBigInteger('total_tuition_fee')->default(0);
            $table->unsignedBigInteger('total_misc_fee')->default(0);
            $table->unsignedDouble('enrollment_tuition_fee')->default(0);
            $table->unsignedDouble('enrollment_misc_fee')->default(0);
            $table->unsignedDouble('monthly_tuition_fee')->default(0);
            $table->unsignedDouble('monthly_misc_fee')->default(0);
            /*department config*/
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();

            $table->foreign('department_id')->nullable()->references('id')->on('departments');
            $table->foreign('grade_id')->nullable()->references('id')->on('grades');
            $table->foreign('school_year_id')->nullable()->references('id')->on('school_years');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_year_fees');
    }
}
