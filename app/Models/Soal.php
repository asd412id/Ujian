<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
  use SoftDeletes;
  protected $table = 'soal';

  public function mapel()
  {
    return $this->belongsTo(Mapel::class,'kode_mapel','kode');
  }
  public function item()
  {
    return $this->hasMany(ItemSoal::class,'kode_soal','kode');
  }
  public function tes()
  {
    return $this->hasMany(Tes::class,'kode_soal','kode');
  }
  public function jadwal()
  {
    return $this->hasOne(JadwalUjian::class,'kode_soal','kode');
  }
}
