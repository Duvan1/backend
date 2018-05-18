<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente = Empleado::all();
        return response()->json(array(
        'cliente'=> $cliente,
        'status'=>'success'
        ), 200);
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
                'nombre' => 'required',
                'apellidos'=> 'required',
                'direccion' => 'required',
                'telefono'=> 'required'
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Guarda objeto
            $cliente = new Cliente();
            $cliente->cedula = $params->cedula;
            $cliente->nombre = $params->nombre;
            $cliente->apellidos = $params->apellidos;
            $cliente->direccion = $params->direccion;
            $cliente->telefono = $params->telefono;
            
            $cliente->save();

            //devolver el arreglo con el vehiculo
            $data = array (
                'cliente'=> $cliente,
                'status' => 'success',
                'code' => 200,
                'message' => 'cliente melo'
            );
        }else{
            //Error
            $data = array (
                'status' => 'error',
                'code' => 300,
                'message' => 'cliente No melo'

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
        $cliente = Cliente::where('cedula', $id)->first();//->load('user');
        return response()->json(array(
            'cliente'=> $cliente, 
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
                'cliente'=> $cliente,
                'status' => 'success',
                'code' => 200,
                'message' => 'cliente melo'
            ]);
            if ($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            //Actualizar registro
            $cliente = Cliente::where('cedula', $id)->update($params_array);
            $data = array(
                'cliente' => $params,
                'status'=> 'success',
                'code'=> 200
                );

        }else{
            $data = array(
                'message' => 'vhybjnk',
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
    public function destroy($id)
    {
        //
    }
}
