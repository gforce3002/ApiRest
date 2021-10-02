<?php

namespace App\Controllers;
use App\Models\CursosModel;
use App\Models\ClientesModel;


class Cursos extends BaseController
{   
    /**
     * Funcion para validar el usuario que viene por token
     */
    private function validateToken($token){
        $clienteModel = new ClientesModel();
        $clientes = $clienteModel->findAll();
        foreach($clientes as $key=>$value){
            if($token === base64_encode($value["idCliente"].":".$value["secret_key"])){
                return array("Authorization"=>true,"_idUser"=>$value["id"]);
            }
        }
        return array("Authorization"=>false);
    }

    /**
     * funcion para mostrar todos los registros
     */
    public function index(){
        //Agregamos el methodo para leer las cabeceras del http
        $request = \Config\Services::request();
        $headers = $request->getHeaders('Authorization');
       
        /**
         * Obtenemos el acceso a la base de datos de clientes
        */  

        $clienteModel = new ClientesModel();
        $clientes = $clienteModel->findAll();
        $cadena = str_replace("Authorization: Basic ","",$request->getHeader('Authorization'));     
        foreach($clientes as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers["Authorization"])){
                if($cadena === base64_encode($value["idCliente"].":".$value["secret_key"])){
                    $cursosModel = new CursosModel();
                    $cursos = $cursosModel->findAll();
                    if(!empty($cursos)){
                        $json = array("status"=>200, "total_resul"=> count($cursos), "detalles"=>$cursos);
                        
                    }else{
                        $json = array("status"=>404, "detalles"=>"Ningun curso encontrado");
                    }
                    return json_encode($json, true);
                }else{
                    $json = array("status"=>404, "detalles"=>"Tu usuario no esta autorizado");
                }
            }else{
                $json = array("status"=>404, "detalles"=>"Se necesitan las credenciales para utilizar esta api");
            }
        }
        return json_encode($json,true);        
    }
    /**
     * Funcion para crear un nuevo curso
     */
    public function create(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $cadena = str_replace("Authorization: Basic ","",$request->getHeader('Authorization'));     
        $token = $this->validateToken($cadena);
        if($token["Authorization"]){
            $datos = $request->getVar();
            print_r($request);
            if(!empty($datos)){
                $validation->setRules([
                    "titulo"=>"required|string|max_length[255]",
                    "descripcion"=>"required|string|max_length[255]",
                    "instructor"=>"required|string|max_length[255]",
                    "imagen"=>"required|string|max_length[255]",
                    "precio"=>"required|numeric",
                ]);
                $validation->withRequest($this->request)->run();
                if($validation->getErrors()){
                    $json = array("Status"=>404,"detalles"=>$validation->getErrors());
                }else{
                    $datos["id_creador"] = $token["_idUser"];
                    $cursosModel = new CursosModel();
                    $cursosModel->save($datos);
                    $json = array('status'=>200, 'detalle'=> "El curso ha sido creado satisfactoriamente", "data"=>$datos);
                }

                
                
            }else{  
                $json = array('status'=>404, 'detalle'=> "Datos no recibidos");
                
            }
        }else{
            $json = array('status'=>404, 'detalle'=> "Requiere credenciales de acceso");
        }
           
        return json_encode($json, true);
    }

    /**
     * funcion para obtener un solo registro de cursos
     * 
     * @param (INTEGER) id
     */

    public function show($id){
    
    $request = \Config\Services::request();
    $cadena = str_replace("Authorization: Basic ","",$request->getHeader('Authorization'));
    $token = $this->validateToken($cadena);
    if($token["Authorization"]){
        $cursosModel = new CursosModel();
        $curso = $cursosModel->find($id);
        if(!empty($curso)){
            $json = array("status"=>200, "detalles"=>$curso);
        }else{
            $json = array("status"=>404, "detalles"=>"No hay ningun curso registro");
        }
        
    }else{
        $json = array('status'=>404, 'detalle'=> "Requiere credenciales de acceso");
    }
    return json_encode($json, true);
    }

    public function update($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $cadena = str_replace("Authorization: Basic ","",$request->getHeader('Authorization'));
        $token = $this->validateToken($cadena);
        if($token["Authorization"]){
            //parse_str($request->getBody(),$datos);
            $datos = $this->request->getRawInput();
            if(!empty($datos)){
                $validation->setRules([
                    "titulo"=>"required|string|max_length[255]",
                    "descripcion"=>"required|string|max_length[255]",
                    "instructor"=>"required|string|max_length[255]",
                    "imagen"=>"required|string|max_length[255]",
                    "precio"=>"required|numeric",
                ]);
                $validation->withRequest($this->request)->run();
                if($validation->getErrors()){
                    $json = array("Status"=>404,"detalles"=>$validation->getErrors());
                }else{
                    $cursosModel = new CursosModel();
                    $curso = $cursosModel->find($id);
                    
                    if($curso["id_creador"]===$token["_idUser"]){
                        $cursosModel->update($id, $datos);
                        $json = array('status'=>200, 'detalle'=> "El curso se ha actualizado satisfactoriamente");
                    }else{
                        $json = array('status'=>404, 'detalle'=> "Lo sentimos no tienes permisos para actualizar el curso");
                    }
                    
                }
            }else{
                $json = array('status'=>404, 'detalle'=> "Los datos del formulario vienen vacios");
            }
            
        }else{
            $json = array('status'=>404, 'detalle'=> "Requiere credenciales de acceso");
        }
        return json_encode($json,true);
    }

    public function delete($id){
        $request = \Config\Services::request();
        $cadena = str_replace("Authorization: Basic ","",$request->getHeader('Authorization'));
        $token = $this->validateToken($cadena);
        if($token["Authorization"]){
            $cursosModel = new CursosModel();
            $curso = $cursosModel->find($id);
            if($curso["id_creador"]===$token["_idUser"]){
                $cursosModel->delete($id);
                $json = array("status"=>200, "detalles"=>"El curso ha sido eliminado");
            }else{
                $json = array("status"=>404, "detalles"=>"No esta autorizado ");
            }
            
        }else{
            $json = array('status'=>404, 'detalle'=> "Requiere credenciales de acceso");
        }   
        return json_encode($json, true);
    }
}