<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('kode');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('propinsi')->nullable();
            $table->integer('kodepos')->unsigned()->nullable();
            $table->string('telp')->nullable();
            $table->string('fax')->nullable();
            $table->text('kop_kartu')->nullable();
            $table->string('logo')->nullable();
            $table->string('dept_logo')->nullable();
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
        Schema::dropIfExists('sekolah');
    }
}
