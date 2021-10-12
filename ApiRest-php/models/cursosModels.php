<?php
    require_once 'Conexion.php';

    class cursosModels{
        static public function index($tabla, $skip=null, $limit=null){
            $limit = !is_null($skip) && !is_null($limit)? "LIMIT $skip, $limit":"";
            $sql = "SELECT CU.*,CL.nombres, CL.apellidos  FROM $tabla as CU 
            left join clientes as CL on (CU.id_creador = CL.id) $limit";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS); //PDO::FETCH_CLASS: permite obtener los fetch de consulta solo por campós y no indices

        }

        static public function create($tabla, $datos){
            $res = array("Bandera"=>false, "Mensaje"=>"");
            $sql = "INSERT INTO $tabla(titulo, descripcion, instructor, imagen, precio, id_creador, created_at, updated_at)"
                ."values(:titulo, :descripcion, :instructor, :imagen, :precio, :id_creador, :created_at, :updated_at)";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->bindParam(':titulo', $datos["titulo"], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(':instructor', $datos["instructor"], PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $datos["imagen"], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $datos["precio"], PDO::PARAM_STR);
            $stmt->bindParam(':id_creador', $datos["id_creador"], PDO::PARAM_STR);
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

        static public function show($table, $id){
            $sql = "SELECT CU.*,CL.nombres, CL.apellidos FROM $table as CU 
            left join clientes as CL on (CU.id_creador = CL.id) where CU.id=:id";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); //PDO::FETCH_CLASS: permite obtener los fetch de consulta solo por campós y no indices
        }

        static public function update($table, $id, $datos){
            $res = array("Bandera"=>false, "Mensaje"=>"");
            $sql = "UPDATE $table SET titulo = :titulo, descripcion = :descripcion, 
            instructor = :instructor, imagen = :imagen, precio = :precio, 
            updated_at = :updated_at 
            where id = :id";    
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $datos["titulo"], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(':instructor', $datos["instructor"], PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $datos["imagen"], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $datos["precio"], PDO::PARAM_STR);
            $stmt->bindParam(':updated_at', $datos["updated_at"], PDO::PARAM_STR);
            if($stmt->execute()){
                $res["Bandera"] = true;
                $res["Mensaje"] = "El registro se ha actualizado satisfactoriamente";
            }else{
                $res["Bandera"] = false;
                $res["Mensaje"] = conexion::conectar()->errorInfo();
            }
            return $res;
            $stmt->close();
            $stmt= null;
        }

        static public function delete($table, $id){
            print_r($id);
            $res = array("Bandera"=>false, "Mensaje"=>"");
            $sql = "DELETE FROM $table where id = :id";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($stmt->execute()){
                $res["Bandera"] = true;
                $res["Mensaje"] = "El registro se ha sido eliminado";
            }else{
                $res["Bandera"] = false;
                $res["Mensaje"] = conexion::conectar()->errorInfo();
            }
            return $res;
            $stmt->close();
            $stmt= null;
        }
    }