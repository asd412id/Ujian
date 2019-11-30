<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
  use SoftDeletes;
  protected $table = 'mapel';

  public function soal()
  {
    return $this->hasMany(Soal::class,'kode_mapel','kode');
  }
}
