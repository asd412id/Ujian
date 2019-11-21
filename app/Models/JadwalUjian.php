<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalUjian extends Model
{
  use SoftDeletes;
  protected $table = 'jadwal_ujian';

  public function kelas()
  {
    return $this->belongsTo(Kelas::class,'kode_kelas','kode');
  }

  public function getSoal()
  {
    return $this->belongsTo(Soal::class,'kode_soal','kode');
  }

  public function login()
  {
    return $this->hasMany(Login::class,'pin','pin');
  }

  public function tes()
  {
    return $this->hasMany(Tes::class,'pin','pin');
  }
}
