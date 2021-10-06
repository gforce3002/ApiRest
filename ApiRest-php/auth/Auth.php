<?php
    class Auth{
        static public function validar(){
            $array = array("Auth"=>false, "msg"=>"");
            if(isset($_SERVER["PHP_AUTH_USER"]) && isset($_SERVER["PHP_AUTH_PW"])){
                if(clientesModels::validateToken('clientes',$_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"])){
                    $array["Auth"] = true;
                    $array["msg"] = "";
                }else{
                    $array["Auth"] = false;
                    $array["msg"] = "El token es invalido";
                }
            }else{
                $array["Auth"] = false;
                $array["msg"] = "Requiere autentificacion";
            }
            return $array;
        }
    }