<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalUjianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_ujian', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('kode_soal');
            $table->string('kode_kelas');
            $table->dateTime('mulai_ujian');
            $table->dateTime('selesai_ujian');
            $table->integer('lama_ujian');
            $table->integer('sesi_ujian');
            $table->string('ruang_ujian')->nullable();
            $table->string('pin');
            $table->string('acak_soal',1)->default('N');
            $table->string('tampil_nilai',1)->default('N');
            $table->tinyInteger('aktif')->nullable();
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
        Schema::dropIfExists('item_soal');
    }
}
