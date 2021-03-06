<?php 
    $arrayRutas = explode ("/",strtok( $_SERVER['REQUEST_URI'],'?'));
    $rutas = array_filter($arrayRutas);
   

    if(count($rutas)==0){
        $json = array("status"=>404, "detalles"=>"No encontrado");
    }else{
        /**
         * 
         */
        if(count($rutas)==1){
            switch($rutas[1]){
                case'registro':
                    if(isset($_SERVER["REQUEST_METHOD"])){
                        $registro = new clientesController();
                        switch($_SERVER["REQUEST_METHOD"]){
                            case 'POST':
                                $datos = array_map("htmlspecialchars",$_POST);
                                $registro->create($datos);
                                break;
                            default:
                                $json = array("status"=>404, "detalle"=>"Detalle no encontrado");
                                print json_encode($json, true);
                                break;
                        }
                    }
                    break;
                case 'cursos':
                    $curso = new cursosController();

                    if(isset($_SERVER["REQUEST_METHOD"])){
                        switch($_SERVER["REQUEST_METHOD"]){
                            case 'GET':
                                if(isset($_GET["pages"]) && is_numeric($_GET["pages"])){
                                    $curso->index($_GET["pages"]); 
                                }else{
                                    $curso->index();
                                }
                                
                                break;
                            case 'POST':
                                $datos = array_map("htmlspecialchars",$_POST);
                                $curso->create($datos);
                                break;
                            default:
                                $json = array("status"=>404, "detalle"=>"Detalle no encontrado");
                                print json_encode($json, true);
                                break;
                        }
                    }
                    break;
                default:
                    $json = array("status"=>404, "detalle"=>"Ruta no encontrada");
                    print json_encode($json, true);
                break;
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
                            //convertimos el string en un array 
                            parse_str(file_get_contents('php://input'),$datos);
                            $curso->update($rutas[2],$datos);
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

            }else{
                $json = array("status"=>404, "detalle"=>"Ruta no encontrada");
                    print json_encode($json, true);
            }
        }
    }
    

    