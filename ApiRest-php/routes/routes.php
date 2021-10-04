<?php 
    $arrayRutas = explode ("/",$_SERVER['REQUEST_URI']);
    $rutas = array_filter($arrayRutas);
    
    if(count($rutas)==0){
        $json = array("status"=>404, "detalles"=>"No encontrado");
    }else{
        /**
         * 
         */
        if(count($rutas)==1){
            if($rutas[1]=="registros"){
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
                    $registro = new clientesController();
                    $registro->create();
                    
                }
               
                
                return;
            }
            if($rutas[1]=="cursos"){
                $curso = new cursosController();
                $curso->create();
                return;
            }
        }else{
            if($rutas[1]=="cursos" && is_numeric($rutas[2])){
                $json = array("status"=>404, "detalles"=>"Estoy en un registro");
                print json_encode($json, true);
                return;
            }
        }
    }
    

    