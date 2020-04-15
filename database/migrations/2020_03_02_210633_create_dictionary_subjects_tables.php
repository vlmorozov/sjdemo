<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictionarySubjectsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->timestamps();
        });

        $subjects = [
            'Математика',
            'Русский язык',
            'Геометрия',
            'Английский',
        ];

        foreach ($subjects as $subject) {
            \Illuminate\Support\Facades\DB::table('dictionary_subjects')
                ->insert([
                    'title' => $subject
                ]);
        }
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dictionary_subjects');
    }
}
