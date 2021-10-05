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

        static public function create($tabla, $datos){
            $sql = "INSERT INTO $tabla (nombres, apellidos, email, idCliente, secret_key, created_at, updated_at)
             values ('{$datos["nombres"]}','{$datos["apellidos"]}','{$datos["email"]}',
             '{$datos["idCliente"]}','{$datos["secret_key"]}','{$datos["created_at"]}','{$datos["updated_at"]}')";
             $stmt = conexion::conectar()->prepare($sql);
             $stmt->execute();
        }
    }