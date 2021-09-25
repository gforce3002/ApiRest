<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //para realizar validaciones
use Illuminate\Support\Facades\Hash; //para utilizar los hash para encriptacion

//instanciamos el modelo de la base de datos de clientes
use App\Clientes;

class ClientesController extends Controller
{
    public function index(){
        $json = array("detalle"=>"No encontrado");
        echo json_encode($json, true);
    }
    /**
     * Funcion que permite generar un registro de clietes para el metodo POST
     */
    public function store(Request $request){
        //Recogemos los datos
        
        $datos = array("nombres"=> $request->input("nombres"),"apellidos"=> $request->input("apellidos"), "email"=> $request->input("email"));
        
        if(!empty($datos)){
            //Validar datos
            $validator = Validator::make($datos,[
                'nombres'=>'required|string|max:255',
                'apellidos'=>'required|string|max:255',
                'email'=>'required|string|email|max:255|unique:clientes'
            ]);
            // si falla la validacion

            if($validator->fails()){
                $errores = $validator->errors();
                $json = array("status"=>404,"Detalles"=>$errores);
            }else{
                $cliente = new Clientes();
                $cliente->nombres = $datos["nombres"];
                $cliente->apellidos = $datos["apellidos"];
                $cliente->email = $datos["email"];
                $cliente->idCliente = str_replace('$', '-', Hash::make($datos["nombres"].$datos["apellidos"].$datos["email"]));
                $cliente->secret_key = str_replace('$', '-', Hash::make($datos["email"].$datos["nombres"].$datos["apellidos"],["rounds"=>12]));
                $cliente->save();
                $json = array("status"=>200, "detalle"=>"Registro Exitoso, tome sus credenciales y guardelas",
                "credenciales"=>array("idCliente"=>$cliente->idCliente, "secret_key"=>$cliente->secret_key));
                
            }
            return json_encode($json, true);
        }else{
            $json = array("status"=>404, "detalle"=>"Registro no encontrados");
            return json_encode($json,true);
        }
    }
}
