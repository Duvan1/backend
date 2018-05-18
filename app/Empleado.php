<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Empleado extends Model
{
    protected $table = 'empleado';

    public function user(){
        return $this->hasOne('App\User', 'cedula');
    }
}