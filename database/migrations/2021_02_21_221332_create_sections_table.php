<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique()->nullable();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('order');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->foreign('department_id')->nullable()->references('id')->on('departments');
            $table->foreign('grade_id')->nullable()->references('id')->on('grades');
            $table->softDeletes();
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
        Schema::dropIfExists('sections');
    }
}
