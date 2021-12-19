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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer("student_id")->default(100000);
            $table->string('firstname'); //fn ,ln, e, pic, phone
            $table->string('lastname'); //fn ,ln, e, pic, phone
            $table->string('email'); //fn ,ln, e, pic, phone
            $table->string('picture')->nullable(); //fn ,ln, e, pic, phone
            $table->string('phone'); //fn ,ln, e, pic, phone
            $table->bigInteger('grade_id');
            $table->bigInteger('section_id');
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
        Schema::dropIfExists('students');
    }
}