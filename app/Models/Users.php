<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Authenticatable
{
  use SoftDeletes;
  use Notifiable;

  protected $table = 'users';

  protected $guard = 'users';

}
