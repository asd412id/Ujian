<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTesUjianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tes_ujian', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noujian');
            $table->string('pin');
            $table->string('kode_soal');
            $table->uuid('soal_item');
            $table->text('opsi')->nullable();
            $table->string('jawaban')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tes_ujian');
    }
}
