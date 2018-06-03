<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalles_venta extends Model
{
    protected $table = 'detalles_venta'; 

    public function user(){
        return $this->hasMany('App\Venta', 'id');
    }

    public function producto(){
        return $this->hasMany('App\Producto', 'id');
    }
}
