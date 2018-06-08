<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Venta;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
		public function index(){
		    $venta = Venta::all()->load('user');
		    return response()->json(array(
		        'venta'=> $venta,
		        'status'=>'success'
		        ), 200);
    	}

	    public function show($id){
	        $venta = Venta::find($id)->load('user');
	        return response()->json(array(
	        	'venta'=> $venta, 
	        	'status'=> 'success'
	        	), 200);
	    }


        public function store(Request $request){
    	$hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            //recoger los datos
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);
            //identificar usuario
            $user = $jwtAuth->checkToken($hash, true);
            
            $validate = \Validator::make($params_array, [
                //'fecha' => 'required',
                'tipo_pago'=> 'required',
                'dias' => 'required',
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Guarda objeto
            $venta = new Venta();
            $venta->Empleado_cedula = $params->Empleado_cedula;
            $venta->clientes_cedula = $params->clientes_cedula;
            $venta->fecha = new \DateTime();//$params->fecha;
            $venta->tipo_pago = $params->tipo_pago;
            $venta->dias = $params->dias;
            
            $venta->save();

            //devolver el arreglo con el vehiculo
            $data = array (
                'venta'=> $venta,
                'status' => 'success',
                'code' => 200,
                'message' => 'venta melo'
            );
        }else{
            //Error
            $data = array (
                'status' => 'error',
                'code' => 300,
                'message' => 'venta No melo'

            );
        }
        return response()->json($data, 200);
    }


    public function update($id, Request $request){
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            //recoger los datos
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);
            
            $validate = \Validator::make($params_array, [
                'fecha' => 'required',
                'tipo_pago'=> 'required',
                'dias' => 'required',
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Actualizar registro
            $venta = Venta::where('id', $id)->update($params_array);
            $data = array(
                'producto' => $params,
                'status'=> 'success',
                'code'=> 200
                );

        }else{
            $data = array(
                'message' => 'Login incorrecto',
                'status'=> 'error',
                'code'=> 300,
                );
        }
        return response()->json($data, 200);
    }

    public function ganancias($tipo, $value)
    {
        $results;
        if ($tipo == 'day') {
            $results = DB::table('ventas')
            ->join('detalles_venta', 'ventas.id', '=', 'detalles_venta.venta_id')
            ->select(DB::raw('SUM(detalles_venta.total) as total_sales'))
            //->where(DB::raw('month(ventas.fecha)'), '=', 5)
            ->whereBetween('ventas.fecha', [$value, $value])
            ->get();
        }else if($tipo == 'month'){
            $results = DB::table('ventas')
            ->join('detalles_venta', 'ventas.id', '=', 'detalles_venta.venta_id')
            ->select(DB::raw('SUM(detalles_venta.total) as total_sales'))
            ->where(DB::raw('month(ventas.fecha)'), '=', $value)
            //->whereBetween('ventas.fecha', ['2018-06-07', '2018-06-07'])
            ->get();
        }else if($tipo == 'year'){
            $results = DB::table('ventas')
            ->join('detalles_venta', 'ventas.id', '=', 'detalles_venta.venta_id')
            ->select(DB::raw('SUM(detalles_venta.total) as total_sales'))
            ->where(DB::raw('year(ventas.fecha)'), '=', $value)
            //->whereBetween('ventas.fecha', ['2018-06-07', '2018-06-07'])
            ->get();
        }

        $data=array(
            'ganancias'=>$results,
            'status'=>'success',
            'code' => 200
        );
        return response()->json($data, 200);
    }

    /*public function EmpleadosMasVenden()
    {

        $results = DB::table('ventas')
        ->join('detalles_venta', 'detalles_venta.venta_id', '=', 'ventas.id')
        ->select('ventas.id',
                'ventas.clientes_cedula',
                'ventas.Empleado_cedula',
                'detalles_venta.producto_id')
                DB::raw('SUM(detalles_venta.total) as totalsirijillo'),
                DB::raw('SUM(detalles_venta.cantidad) as catidadsirijilla'))
        ->where('ventas.id','=', 'detalles_venta.venta_id')
        ->groupBy('ventas.Empleado_cedula')
        ->orderBy(DB::raw('SUM(detalles_venta.total)'))
        ->limit(10)
        ->get();

        $data=array(
            'empleado'=>$results,
            'status'=>'success',
            'code' => 200
        );
        return response()->json($data, 200);
    }*/

}
