<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Detalles_venta;

class Detalles_ventaController extends Controller
{
	public function index(){
    $detalles_venta = Detalles_venta::all()->load('user');
    return response()->json(array(
        'detalles'=> $detalles_venta,
        'status'=>'success'
        ), 200);
	}

    public function show($id){
    $detalles_venta = Detalles_venta::find($id)->load('user');
    return response()->json(array(
    	'detalles'=> $detalles_venta, 
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
                'cantidad' => 'required',
                'total'=> 'required',
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Guarda objeto
            $detalles_venta = new Detalles_venta();
            //$detalles_venta->id = $params->id;
            $detalles_venta->venta_id = $params->venta_id;
            $detalles_venta->producto_id = $params->producto_id;
            $detalles_venta->cantidad = $params->cantidad;
            $detalles_venta->total = $params->total;            
            $detalles_venta->save();

            //devolver el arreglo con el vehiculo
            $data = array (
                'detalles_venta'=> $detalles_venta,
                'status' => 'success',
                'code' => 200,
                'message' => 'detalles_venta melo'
            );
        }else{
            //Error
            $data = array (
                'status' => 'error',
                'code' => 300,
                'message' => 'detalles_venta No melo'

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
                'cantidad' => 'required',
                'total'=> 'required',
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Actualizar registro
            $detalles_venta = Detalles_venta::where('id', $id)->update($params_array);
            $data = array(
                'detalles_venta' => $params,
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
