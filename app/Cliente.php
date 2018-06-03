<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    public function user(){
        return $this->hasMany('App\Venta', 'clientes_cedula');
    }

}
