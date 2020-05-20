<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Login extends Model
{
  use SoftDeletes;
  protected $table = 'login';
  protected $fillable = ['current_number','end'];
  protected $dates = ['start','end','created_at','updated_at','deleted_at'];

  public function siswa()
  {
    return $this->belongsTo(Siswa::class,'noujian','noujian');
  }
  public function jadwal()
  {
    return $this->belongsTo(JadwalUjian::class,'pin','pin');
  }

  public function tes()
  {
    return $this->hasMany(Tes::class,'pin','pin');
  }
}
