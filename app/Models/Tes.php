<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tes extends Model
{
  use SoftDeletes;
  protected $table = 'tes_ujian';

  public function getSoal()
  {
    return $this->belongsTo(Soal::class,'kode_soal','kode');
  }
  public function jadwal()
  {
    return $this->belongsTo(JadwalUjian::class,'pin','pin');
  }
  public function siswa()
  {
    return $this->belongsTo(Siswa::class,'noujian','noujian');
  }
  public function soalItem()
  {
    return $this->belongsTo(ItemSoal::class,'soal_item','uuid');
  }
}
