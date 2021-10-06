<?php
    class clientesController{
        /**
         * Creacion de un registro
         */
        private function validateData ($data){
            if(isset($data["nombres"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/',$data["nombres"])){
                $json = array("status"=>404, "detalles"=>"Error, no se permiten numeros en el nombre del usuario");
                print json_encode($json, true);
                return false;
            }
            if(isset($data["apellidos"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/',$data["apellidos"])){
                $json = array("status"=>404, "detalles"=>"Error, no se permiten numeros en el apellido del usuario");
                print json_encode($json, true);
                return false;
            }
            if(isset($data["email"]) && !preg_match('/^(([^<>()\[\]\\.,;:\s@”]+(\.[^<>()\[\]\\.,;:\s@”]+)*)|(“.+”))@((\[[0–9]{1,3}\.[0–9]{1,3}\.[0–9]{1,3}\.[0–9]{1,3}])|(([a-zA-Z\-0–9]+\.)+[a-zA-Z]{2,}))$/',$data["email"])){
                $json = array("status"=>404, "detalles"=>"Error, el email no es valido");
                print json_encode($json, true);
                return false;
            
            }
            if(isset($data["email"])){
                $conn = clientesModels::validateEmail('clientes',$data["email"]);
                if($conn != 0){
                    $json = array("status"=>404, "detalles"=>"Error, El email {$data["email"]} ya esta registrado");
                    print json_encode($json, true);
                    return false;
                }
            }
            
            
            return true;
        }
        public function create($datos){
            /**
             * Validacion de datos
             */
            if($this->validateData($datos)){
                $datos["idCliente"] = str_replace('$','a',crypt($datos["nombres"].$datos["apellidos"].$datos["email"], '$2a$07$asdNHaEeSGealhjlkjheWERWwwerWER$'));
                $datos["secret_key"] = str_replace('$','o',crypt($datos["email"].$datos["nombres"].$datos["apellidos"], '$2a$07$asdNHaEeSGealhjlkjheWERWwwerWER$'));
                $datos["created_at"] = date('Y-m-d h:i:s');
                $datos["updated_at"] = date('Y-m-d h:i:s');
                $conn = clientesModels::create("clientes", $datos);
                if($conn["Bandera"]){
                    $json = array("status"=>200, "detalles"=>$conn["Mensaje"], 
                    "credenciales"=> array("idCliente"=>$datos["idCliente"], "secret_key"=>$datos["secret_key"]));
                }else{
                    $json = array("status"=>404, "detalles"=>$conn["Mensaje"]);
                }
                
                print json_encode($json, true);
            }
            
        }
    }