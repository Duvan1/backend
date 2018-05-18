<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas'; 
    public function user(){
        return $this->belongsTo('App\User', 'cedula');
    }
    public function cli(){
        return $this->belongsTo('App\User', 'cedula');
    }
}