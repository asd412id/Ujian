<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemSoal extends Model
{
  use SoftDeletes;
  protected $table = 'item_soal';

  public function getSoal()
  {
    return $this->belongsTo(Soal::class,'kode_soal','kode');
  }

  public function tes()
  {
    return $this->hasMany(Tes::class,'soal_item','uuid');
  }
}
