<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;
use App\User;

class UserController extends Controller
{   
    public function register(Request $request){

  	if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
		    // Ignores notices and reports all other kinds... and warnings
		    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		    // error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
		}
    	//Recoger los post
    	$json = $request->input('json', null);
    	$params = json_decode($json);
    	
        $rol =(!is_null($json) && isset($params->rol)) ? $params->rol : null;
    	$username =(!is_null($json) && isset($params->username)) ? $params->username : null;
        $empleado_cedula =(!is_null($json) && isset($params->empleado_cedula)) ? $params->empleado_cedula : null;
    	$password = (!is_null($json) && isset($params->password)) ? $params->password : null;
    	if( !is_null($password) && !is_null($username)&& !is_null($empleado_cedula) && !is_null($rol)){
    		//crear el usuario
    		$user = new User();
    		$user ->username = $username;
            $user ->empleado_cedula = $empleado_cedula;
            $user ->rol = $rol;


    		$pwd = hash('sha256', $password);
    		$user->password = $pwd;

    		//Validar que el usuario no este registrado
    		$isset_user = User::where('username', '=', $username)->first();

    		if(count($isset_user) == 0){
	    			$user->save();

	    			$data = array(
	    			'status' => 'melo',
	    			'code' => 200,
	    			'message' => 'Usuario creado'
    			);	

    		}else{
	    		$data = array(
	    			'status' => 'error',
	    			'code' => 400,
	    			'message' => 'Usuario no creado'
    			);
    		}

    	}else{
    		$data = array(
    			'status' => 'error',
    			'code' => 400,
    			'message' => 'Usuario no creado'
    			);
    	}
    		return response()->json($data, 200);

    }
    public function login(Request $request){

        $jwtAuth = new JwtAuth();

        //Recibir datos 
        $json = $request->input('json', null);
        $params = json_decode($json);

        $username = (!is_null($json) && isset($params->username)) ? $params ->username: null;
        $password = (!is_null($json) && isset($params->password)) ? $params ->password : null;
        $gettoken = (!is_null($json) && isset($params->gettoken)) ? $params ->gettoken : null;

        // cifrar la contraseña
        $pwd = hash('sha256', $password);
        if(!is_null($username) && !is_null($password) && ($gettoken == null || $gettoken == 'false')){
            $signup = $jwtAuth->signup($username, $pwd);
        }elseif($gettoken != null){
           // var_dump($getToken);die();
            $signup = $jwtAuth->signup($username, $pwd, $gettoken);

        }else{
            $signup = array(
                'status'=> 'error',
                'message' => 'manda bien los datos'
                );
        }
        return response()->json($signup, 200);
    }      

    
}
