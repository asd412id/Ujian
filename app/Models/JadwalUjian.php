<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalUjian extends Model
{
  use SoftDeletes;
  protected $table = 'jadwal_ujian';

  public function login()
  {
    return $this->hasMany(Login::class,'pin','pin');
  }

  public function tes()
  {
    return $this->hasMany(Tes::class,'pin','pin');
  }

  public function siswa()
  {
    $peserta = json_decode($this->peserta);
    return Siswa::whereIn('uuid',$peserta);
  }

  public function getSiswaNotLoginAttribute()
  {
    $pin = $this->pin;
    $peserta = json_decode($this->peserta);
    return Siswa::whereIn('uuid',$peserta)
    ->whereNull('_token')
    ->whereDoesntHave('attemptLogin')
    ->orWhereHas('attemptLogin',function($q) use($pin){
      $q->where('pin',$pin)
      ->whereNull('_token')
      ->whereNull('end');
    });
  }
}
