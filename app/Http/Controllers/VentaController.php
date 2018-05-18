<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Venta;

class VentaController extends Controller
{
		public function index(){
		    $venta = Venta::all()->load('user');
		    return response()->json(array(
		        'productos'=> $venta,
		        'status'=>'success'
		        ), 200);
    	}

	    public function show($id){
	        $venta = Venta::find($id)->load('user');
	        return response()->json(array(
	        	'producto'=> $venta, 
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
                'fecha' => 'required',
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
            $venta->fecha = $params->fecha;
            $venta->tipo_pago = $params->tipo_pago;
            $venta->dias = $params->dias;
            
            $venta->save();

            //devolver el arreglo con el vehiculo
            $data = array (
                'product'=> $venta,
                'status' => 'success',
                'code' => 200,
                'message' => 'venta melo'
            );
        }else{
            //Error
            $data = array (
                'status' => 'error',
                'code' => 300,
                'message' => 'producto No melo'

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

}
