<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
  use SoftDeletes;
  protected $table = 'kelas';

  public function siswa()
  {
    return $this->hasMany(Siswa::class,'kode_kelas','kode');
  }
  public function soal()
  {
    return $this->hasOne(Soal::class,'kode_kelas','kode');
  }
}
