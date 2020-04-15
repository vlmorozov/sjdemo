<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dateTime('dateFrom');
            $table->dateTime('dateTo');
            $table->bigInteger('class_id');
            $table->bigInteger('school_id');
        });
        Schema::create('schedule_subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('schedule_id');
            $table->bigInteger('school_id');
            $table->bigInteger('subject_id');
            $table->bigInteger('teacher_id');
            $table->bigInteger('cabinet_id');
            $table->timestamps();
        });
        Schema::create('schedule_week', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('schedule_id');
            $table->bigInteger('school_id');
            $table->bigInteger('weekday');
            $table->bigInteger('lesson');
            $table->bigInteger('subject_id');
            $table->bigInteger('teacher_id');
            $table->bigInteger('cabinet_id');
            $table->timestamps();
        });
        Schema::create('schedule_lessons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('schedule_id');
            $table->bigInteger('school_id');
            $table->date('date');
            $table->bigInteger('lesson_number');
            $table->bigInteger('subject_id');
            $table->bigInteger('teacher_id');
            $table->bigInteger('cabinet_id');
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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('dateFrom');
            $table->dropColumn('dateTo');
            $table->dropColumn('classes_id');
        });
        Schema::dropIfExists('schedule_subjects');
        Schema::dropIfExists('schedule_week');
        Schema::dropIfExists('schedule_lessons');

    }
}
