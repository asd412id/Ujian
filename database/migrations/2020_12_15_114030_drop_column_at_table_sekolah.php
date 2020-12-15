<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnAtTableSekolah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sekolah', function (Blueprint $table) {
          $table->dropColumn('kota');
          $table->dropColumn('propinsi');
          $table->dropColumn('kodepos');
          $table->dropColumn('telp');
          $table->dropColumn('fax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sekolah', function (Blueprint $table) {
          $table->string('kota')->nullable();
          $table->string('propinsi')->nullable();
          $table->integer('kodepos')->unsigned()->nullable();
          $table->string('telp')->nullable();
          $table->string('fax')->nullable();
        });
    }
}
