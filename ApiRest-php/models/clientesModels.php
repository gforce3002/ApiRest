<?php
    require_once 'Conexion.php';

    class clientesModels{
        static public function index($tabla){
            $sql = "SELECT * FROM $tabla";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();

        }

        static public function validateEmail($tabla, $email=null){
            if(!is_null($email)){
                $sql = "SELECT * FROM $tabla where email = '$email'";
                $stmt = conexion::conectar()->prepare($sql);
                $stmt->execute();
                return $stmt->rowCount();
            }
        }

        static public function validateToken($tabla, $user, $pass){
            $resp = array("Bandera"=>false, "idUser"=>"");
            $sql = "SELECT * FROM $tabla where idCliente='$user' and secret_key='$pass'";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt->execute()){
                if($stmt->rowCount()!=0){
                    $row = $stmt->fetch();
                    $resp["Bandera"] = true;
                    $resp["idUser"] = $row["id"];
                }else{
                    $resp["Bandera"] = false;
                }
            }else{
                $resp["Bandera"] = false;
                $resp["mensaje"] = conexion::conectar()->errorInfo();
            }

            return $resp;
        }

        static public function create($tabla, $datos){
            $res = array("Bandera"=>false, "Mensaje"=>"");
            $sql = "INSERT INTO $tabla (nombres, apellidos, email, idCliente, secret_key, created_at, updated_at)
             values (:nombres, :apellidos, :email, :idCliente, :secret_key, :created_at, :updated_at)";
            $stmt = conexion::conectar()->prepare($sql);
            
            $stmt->bindParam(':nombres', $datos["nombres"], PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $datos["apellidos"], PDO::PARAM_STR);
            $stmt->bindParam(':email', $datos["email"], PDO::PARAM_STR);
            $stmt->bindParam(':idCliente', $datos["idCliente"], PDO::PARAM_STR);
            $stmt->bindParam(':secret_key', $datos["secret_key"], PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $datos["created_at"], PDO::PARAM_STR);
            $stmt->bindParam(':updated_at', $datos["updated_at"], PDO::PARAM_STR);
            if($stmt->execute()){
                $res["Bandera"] = true;
                $res["Mensaje"] = "El registro se ha creado satisfactoriamente";
            }else{
                $res["Bandera"] = false;
                $res["Mensaje"] = conexion::conectar()->errorInfo();
            }
            return $res;
            $stmt->close();
            $stmt= null;
        }
    }