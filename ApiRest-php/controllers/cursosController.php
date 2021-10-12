<?php
    class cursosController{
        
        private function validateData($data){
            $resp = array("Bandera"=>false, "mensaje"=>"");
            $exp = '/^[<\\>\\(\\)\\-\\.\\_\\!\\"\\#\\%\\$\\&\\/\\=\\?\\¡\\¿\\+\\}\\{\\[\\:\\;\\0-9a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/';
            foreach($data as $key => $value){
               if(isset($value) && !preg_match($exp,$value)){
                $resp["Bandera"]=false;
                $resp["mensaje"] = "Error en el campo $key";
                return $resp;
               } 
            }
            $resp["Bandera"]=true;
            return $resp;
        }

        /**
         * Mostrar todos los cursos
         */
        public function index($page=null){
            #print_r($page);
            $autentificar = Auth::validar();
            if($autentificar["Auth"]){
                if(is_null($page)){
                    $cursos = cursosModels::index('Cursos');
                }else{
                    $limit = 10;
                    $skip = ($page-1)*$limit;
                    $cursos = cursosModels::index('Cursos',$skip,$limit);
                }
                
                if(count($cursos)!=0){
                    
                    $json = array("status"=>200, "Total_registros"=>count($cursos), "detalles"=>$cursos);
                }else{
                    $json = array("status"=>200, "Total_registros"=>count($cursos), "detalles"=>"No hay cursos a mostrar");
                }
                
            }else{
                $json = array("status"=>404, "detalles"=>$autentificar["msg"]);
            }
            print json_encode($json, true);
        }
        /**
         * Creacion de un registro
         */

        public function create($datos){
            $autentificar = Auth::validar();
            if($autentificar["Auth"]){
                if(isset($datos)){
                    $validate = $this->validateData($datos);
                    if($validate["Bandera"]){
                        
                        $datos["id_creador"] = $autentificar["idUser"];
                        $datos["created_at"] = date('Y-m-d h:i:s');
                        $datos["updated_at"] = date('Y-m-d h:i:s');
                        $conn = cursosModels::create("Cursos", $datos);
                        if($conn["Bandera"]){
                            $json = array("status"=>200, "detalles"=>$conn["Mensaje"]);
                        }else{
                            $json = array("status"=>404, "detalles"=>$conn["Mensaje"]);
                        }
                        
                    }else{
                        $json = array("status"=>404, "detalles"=>$validate["mensaje"]);
                    }
                }else{
                    $json = array("status"=>404, "detalles"=>"No hay datos a regitrar");
                }
            }else{
                $json = array("status"=>404, "detalles"=>$autentificar["msg"]);
            }
            
            
            print json_encode($json, true);
        }

        /**
         * Modificacion de un curso
         * @param (INTEGER) $id  Recibe como parametro el id del curso a modificar
         */
        public function update($id,$datos){
            $autentificar = Auth::validar();
            if($autentificar["Auth"]){
                $curso = cursosModels::show('Cursos',$id);
                if($curso["id_creador"] == $autentificar["idUser"]){
                    if(isset($datos)){
                        $validate = $this->validateData($datos);
                        if($validate["Bandera"]){
                            $datos["updated_at"] = date('Y-m-d h:i:s');
                            $curso = cursosModels::update('Cursos',$id,$datos);
                            $json = array("status"=>200, "detalles"=>$curso);
                        }else{
                            $json = array("status"=>404, "detalles"=>$validate["mensaje"]); 
                        }
                    }else{
                        $json = array("status"=>404, "detalles"=>"No existe el id a buscar");
                    }
                }else{
                    $json = array("status"=>404, "detalles"=>"No tienes permisos para modificar este curso");
                }
                
            }else{
                $json = array("status"=>404, "detalles"=>$autentificar["msg"]); 
            }
            print json_encode($json, true);
        }

         /**
         * Eliminacion de un curso
         * @param (INTEGER) $id  Recibe como parametro el id del curso a eliminar
         */
        public function delete($id){
            $autentificar = Auth::validar();
            if($autentificar["Auth"]){
                $curso = cursosModels::show('Cursos',$id);
                if($curso["id_creador"] == $autentificar["idUser"]){
                    $curso = cursosModels::delete('Cursos',$id);
                    if($curso["Bandera"]){
                        $json = array("status"=>200, "detalles"=>$curso["Mensaje"]);    
                    }else{
                        $json = array("status"=>404, "detalles"=>$curso["Mensaje"]);    
                    }
                }else{
                    $json = array("status"=>404, "detalles"=>"No tienes permisos para eliminar este curso");
                }
            }else{
                $json = array("status"=>404, "detalles"=>$autentificar["msg"]); 
            }
            print json_encode($json, true);
        }
         /**
         * Buscar un curso
         * @param (INTEGER) $id  Recibe como parametro el id del curso a buscar
         */
        public function show($id){
            $autentificar = Auth::validar();
            if($autentificar["Auth"]){
                if(isset($id)){
                    $curso = cursosModels::show('Cursos',$id);
                    $json = array("status"=>200, "detalles"=>$curso);
                }else{
                    $json = array("status"=>404, "detalles"=>"No existe el id a buscar");
                }
            }else{
                $json = array("status"=>404, "detalles"=>$autentificar["msg"]); 
            }
            
            print json_encode($json, true);
        }
    }