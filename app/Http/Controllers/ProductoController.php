<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Producto;

class ProductoController extends Controller
{
    public function index(/*Request $request*/){

        
        $producto = Producto::all()->load('user');
        return response()->json(array(
            'productos'=> $producto,
            'status'=>'success'
            ), 200);
        /*$hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if ($checkToken) {
            echo "logeado melo ompa"; die();
        } else {
            echo "logeado no melo ompa"; die();
        }*/
        
    }


    public function show($id){
        $producto = Producto::find($id)->load('user');
        return response()->json(array(
        	'producto'=> $producto, 
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
                'codigo' => 'required',
                'nombre' => 'required',
                'description'=> 'required',
                'cantidad' => 'required',
                'precio_estandar' => 'required',
                'categoria'=> 'required'
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Guarda objeto
            $producto = new Producto();
            $producto->Empleado_cedula = $params->Empleado_cedula;
            $producto->codigo = $params->codigo;
            $producto->nombre = $params->nombre;
            $producto->description = $params->description;
            $producto->cantidad = $params->cantidad;
            $producto->precio_estandar = $params->precio_estandar;
            $producto->descuento_paquete = $params->descuento_paquete;
            $producto->categoria = $params->categoria;
            
            $producto->save();

            //devolver el arreglo con el vehiculo
            $data = array (
                'product'=> $producto,
                'status' => 'success',
                'code' => 200,
                'message' => 'producto melo'
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
                'codigo' => 'required',
                'nombre' => 'required',
                'description'=> 'required',
                'cantidad' => 'required',
                'precio_estandar' => 'required',
                'categoria'=> 'required'
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Actualizar registro
            $producto = Producto::where('id', $id)->update($params_array);
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
    
    public function destroy($id, Request $request){
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){
            //validar que exista
            $producto = Producto::find($id);
            //Borrarlo
            $producto->delete();
            //Devolverlo
            $data=array(
                'producto'=>$producto,
                'status'=>'success',
                'code' => 200
            );
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message'=> 'Login incorrecto'
            );

        }
        return response()->json($data, 200);
    }

    public function tuHermana()
    {
    	$perro_mestizo = new Producto();
    	$coso = $perro_mestizo->consulPrue();
    	//dd($coso);
    	$data=array(
            'producto'=>$coso,
            'status'=>'success',
            'code' => 200
        );
    	return response()->json($data, 200);

    }

}
