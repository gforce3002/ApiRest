<?php
    require_once 'Conexion.php';

    class cursosModels{
        static public function index($tabla){
            $sql = "SELECT * FROM $tabla";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS); //PDO::FETCH_CLASS: permite obtener los fetch de consulta solo por campós y no indices

        }
    }