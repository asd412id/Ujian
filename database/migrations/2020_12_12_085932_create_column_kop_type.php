<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnKopType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->string('kop_type',10)->after('dept_logo')->nullable();
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
            $table->dropColumn('kop_type');
        });
    }
}
