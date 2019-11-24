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
            $table->string('nama_ujian');
            $table->tinyInteger('jumlah_soal');
            $table->text('soal');
            $table->text('peserta');
            $table->string('jenis_soal',1)->default('P');
            $table->tinyInteger('bobot');
            $table->dateTime('mulai_ujian');
            $table->dateTime('selesai_ujian');
            $table->integer('lama_ujian');
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
