<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{
	public $key;
	public function __construct(){
		$this->key= 'clavesita-236%12913';
	}
	public function signup($username,$password, $getToken= null){
		$user = User::where(
			array(
				'password'=> $password,
				'username'=> $username
			))->first();

		$signup = false;
		if(is_object($user)){
			$signup = true;
		}
		if($signup){

			$token = array(
				'sub' => $user->Empleado_cedula,
				'username' => $user->username,
				'password'=> $user->password,
				'rol' => $user->rol,
				'iat' => time(),
				'exp' => time() + (7 * 24*60 * 60)
			);
			$jwt = JWT::encode($token, $this->key, 'HS256');
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));

			if(is_null($getToken)){
				return $jwt;
			}else{
				return $decoded;
			}

		}else{
			return array(
				'status'=> 'error', 
				'message'=> 'No se logeo'
				);
		}
	}



	public function checkToken($jwt, $getIdentity = false){
		$auth = false;
		try {
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));
		}catch(\UnexpectedValueException $e){
			$auth = false;
		}catch(\DomainException $e){
			$auth = false;
		}
		if(isset($decoded) && is_object($decoded) && isset($decoded->sub)){
			$auth = true;
		}else{
			$auth = false;
		}
		if($getIdentity){
			return $decoded;
		}

		return $auth;
	}

}