<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Empleado;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleado = Empleado::all();
        return response()->json(array(
        'empleado'=> $empleado,
        'status'=>'success'
        ), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
                'cedula' => 'required',
                'Nombre' => 'required',
                'Apellido'=> 'required',
                'Cargo' => 'required',
                'direccion' => 'required',
                'telefono'=> 'required',
                'correo'=> 'required'
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Guarda objeto
            $empleado = new Empleado();
            $empleado->cedula = $params->cedula;
            $empleado->Nombre = $params->Nombre;
            $empleado->Apellido = $params->Apellido;
            $empleado->Cargo = $params->Cargo;
            $empleado->direccion = $params->direccion;
            $empleado->telefono = $params->telefono;
            $empleado->correo = $params->correo;
            $empleado->estado = true;
            
            $empleado->save();

            //devolver el arreglo con el vehiculo
            $data = array (
                'empleado'=> $empleado,
                'status' => 'success',
                'code' => 200,
                'message' => 'empleado melo'
            );
        }else{
            //Error
            $data = array (
                'status' => 'error',
                'code' => 300,
                'message' => 'empleado No melo'

            );
        }
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$empleado = Empleado::find($id);
        $empleado = Empleado::where('cedula', $id)->first();//->load('user');
        return response()->json(array(
            'empleado'=> $empleado, 
            'status'=> 'success'
            ), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            //recoger los datos
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);
            
            $validate = \Validator::make($params_array, [
                'cedula' => 'required',
                'Nombre' => 'required',
                'Apellido'=> 'required',
                'Cargo' => 'required',
                'direccion' => 'required',
                'telefono'=> 'required',
                'correo'=> 'required'
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Actualizar registro
            $empleado = Empleado::where('cedula', $id)->update($params_array);
            $data = array(
                'empleado' => $params,
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
        
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request){
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){
            //validar que exista
            //$empleado = Empleado::find($cedula);
            $empleado = Empleado::where('cedula', $id)->update(['estado' => false]);
            //Borrarlo
            //$empleado->delete();
            //Devolverlo
            $data=array(
                'empleado'=>$empleado,
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
}
