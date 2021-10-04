<?php 
    $arrayRutas = explode ("/",$_SERVER['REQUEST_URI']);
    $rutas = array_filter($arrayRutas);
    var_dump($rutas);
    if(count($rutas)==0){
        $json = array("status"=>404, "detalles"=>"No encontrado");
    }else{
        /**
         * 
         */
        if(count($rutas)==1){
            if($rutas[1]=="registro"){
                $json = array("status"=>404, "detalles"=>"Estoy en registros");
                print json_encode($json, true);
                return;
            }
            if($rutas[1]=="cursos"){
                $json = array("status"=>404, "detalles"=>"Estoy en cursos");
                print json_encode($json, true);
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
    

    