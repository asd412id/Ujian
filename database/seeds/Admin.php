<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Users;

class Admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new Users;
      $admin->truncate();
      $admin->insert([
        'uuid'=>(string)Str::uuid(),
        'username'=>'admin',
        'password'=>bcrypt('password'),
        'nama'=>'Administrator'
      ]);
    }
}
