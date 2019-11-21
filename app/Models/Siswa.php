<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Authenticatable
{
  use SoftDeletes;
  use Notifiable;

  protected $table = 'siswa';

  protected $guard = 'siswa';

  public function kelas()
  {
    return $this->belongsTo(Kelas::class,'kode_kelas','kode');
  }

  public function attemptLogin()
  {
    return $this->hasOne(Login::class,'noujian','noujian');
  }

  public function login()
  {
    return $this->hasOne(Login::class,'_token','remember_token');
  }

  public function tes()
  {
    return $this->hasmany(Tes::class,'noujian','noujian');
  }
}
