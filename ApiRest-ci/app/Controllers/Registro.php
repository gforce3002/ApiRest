<?php

namespace App\Controllers;

class Registro extends BaseController{
    public function index(){
        $json = array("detalles:"=> "Detalles no encontrados");
         return json_encode($json, true);
    }

    /**
     * Crear un registro
     */
    public function create(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        

        //Obtener Registros
        //$datos = json_decode(json_encode($request->getVar()), true);
        $datos = $request->getVar();
        //Validar los registros
        $validation->setRules([
           "nombre"=>"required|string|max_length[255]",
           "apellido"=>"required|string|max_length[255]",
           "email"=>"required|valid_email|is_unique[clientes.email]" 
        ]);

        $validation->withRequest($this->request)->run();
        
        if($validation->getErrors()){
            $error = $validation->getErrors();
            $json = array("Status"=>404,"detalles"=>$error);
            return json_encode($json,true);
        }else{
            
            $id_cliente = crypt($datos["nombre"].$datos["apellido"].$datos["email"], '$2a$07$asdfzxcvqwerpoiul1234kjmbnhjghwtyteGFRE$');
            $secret_key = crypt($datos["email"].$datos["apellido"].$datos["nombre"], '$2a$07$asdfzxcvqwerpoiul1234kjmbnhjghwtyteGFRE$');
            print_r($id_cliente);
            print_r($secret_key);
        }

        
    }
}