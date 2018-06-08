<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Producto extends Model
{
    protected $table = 'productos';

    public function user(){
        return $this->belongsTo('App\User', 'cedula');
    }

    /*public function consulPrue()
    {
    	
    	return DB::table('ventas')
			    	->select('select pr.nombre, sum(dv.cantidad)total 
from venta INNER JOIN detalles_venta dv ON (dv.venta_id = venta.id) 
INNER JOIN productos pr ON (pr.id = dv.producto_id)  
GROUP BY pr.id
ORDER BY total 
DESC limit 3')
			        ->get();
    	
    }*/
}