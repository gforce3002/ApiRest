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
        public function index(){
            $autentificar = Auth::validar();
            if($autentificar["Auth"]){
                $cursos = cursosModels::index('Cursos');
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
        public function update($id){
            $json = array("status"=>404, "detalles"=>"El registro se ha actualizado satisfactoriamente con id: $id");
            print json_encode($json, true);
        }

         /**
         * Eliminacion de un curso
         * @param (INTEGER) $id  Recibe como parametro el id del curso a eliminar
         */
        public function delete($id){
            $json = array("status"=>404, "detalles"=>"El registro se ha eliminado con id: $id");
            print json_encode($json, true);
        }
         /**
         * Buscar un curso
         * @param (INTEGER) $id  Recibe como parametro el id del curso a buscar
         */
        public function show($id){
            $json = array("status"=>404, "detalles"=>"Mostrando el curso con el id: $id");
            print json_encode($json, true);
        }
    }