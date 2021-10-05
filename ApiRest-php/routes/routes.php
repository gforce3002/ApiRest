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

                if(isset($_SERVER["REQUEST_METHOD"])){
                    switch($_SERVER["REQUEST_METHOD"]){
                        case 'GET':
                            $curso->index();
                            break;
                        case 'POST':
                            $curso->create();
                            break;
                    }
                }

                /* if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){
                    $curso->index();
                    return;
                }
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
                    $curso->create();
                    return;
                } */
                
                
                return;
            }
        }else{
            if($rutas[1]=="cursos" && is_numeric($rutas[2])){
                if(isset($_SERVER["REQUEST_METHOD"])){
                    $curso = new cursosController(); 
                    switch($_SERVER["REQUEST_METHOD"]){
                        case 'GET':
                            $curso->show($rutas[2]);
                            break;
                        case 'PUT':
                            $curso->update($rutas[2]);
                            break;
                        case 'DELETE':
                            $curso->delete($rutas[2]);
                            break;
                        default:
                            return;
                        break;
                    }
                    return;
                }else{
                    return;
                }


                /* $curso = new cursosController();
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT"){
                    $curso->update($rutas[2]);
                    return;
                }
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "DELETE"){
                    $curso->delete($rutas[2]);
                    return;
                }
                $json = array("status"=>404, "detalles"=>"Estoy en un registro");
                print json_encode($json, true);
                return; */
            }
        }
    }
    

    