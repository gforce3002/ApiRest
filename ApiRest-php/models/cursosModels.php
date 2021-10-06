<?php
    require_once 'Conexion.php';

    class cursosModels{
        static public function index($tabla){
            $sql = "SELECT * FROM $tabla";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS); //PDO::FETCH_CLASS: permite obtener los fetch de consulta solo por campós y no indices

        }

        static public function create($tabla, $datos){
            $res = array("Bandera"=>false, "Mensaje"=>"");
            $sql = "INSERT INTO $tabla(titulo, descripcion, instructor, imagen, precio, id_creador, created_at, updated_at)"
                ."value(:titulo, :descripcion, :instructor, :imagen, :precio, :id_creador, :created_at, :updated_at)";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->bindParam(':titulo', $datos["titulo"], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(':instructor', $datos["instructor"], PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $datos["imagen"], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $datos["precio"], PDO::PARAM_STR);
            $stmt->bindParam(':id_creador', $datos["id_creador"], PDO::PARAM_STR);
            $stmt->bindParam(':created_id', $datos["created_id"], PDO::PARAM_STR);
            $stmt->bindParam(':updated_id', $datos["updated_id"], PDO::PARAM_STR);
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