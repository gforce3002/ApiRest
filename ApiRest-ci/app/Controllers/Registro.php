<?php

namespace App\Controllers;

use App\Models\ClientesModel;

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
        if(!empty($datos)){
            //Validar los registros
        $validation->setRules([
            "nombres"=>"required|string|max_length[255]",
            "apellidos"=>"required|string|max_length[255]",
            "email"=>"required|valid_email|is_unique[clientes.email]" 
         ]);
 
         $validation->withRequest($this->request)->run();
         
         if($validation->getErrors()){
             $error = $validation->getErrors();
             $json = array("Status"=>404,"detalles"=>$error);
             return json_encode($json,true);
         }else{
             
             $id_cliente = crypt($datos["nombres"].$datos["apellidos"].$datos["email"], '$2a$07$asdfzxcvqwerpoiul1234kjmbnhjghwtyteGFRE$');
             $secret_key = crypt($datos["email"].$datos["apellidos"].$datos["nombres"], '$2a$07$asdfzxcvqwerpoiul1234kjmbnhjghwtyteGFRE$');
             
             $datos["idCliente"]=str_replace('$','a',$id_cliente);
             $datos["secret_key"]=str_replace('$','o',$secret_key);
 
             $clientesModel = new ClientesModel();
             $clientesModel->save($datos);
 
             $json = array('status'=>200, 'detalle'=> "Registro exitoso, tome sus credenciales y guarlelas", 
             "Credenciales"=>array("id_cliente"=>$datos["idCliente"], "llave_secreta"=>$datos["secret_key"]));
 
            return json_encode($json, true);
         }
        }else{
            $json = array('status'=>404, 'detalle'=> "Registro con errores");
            return json_encode($json, true);
        }     
    }
}