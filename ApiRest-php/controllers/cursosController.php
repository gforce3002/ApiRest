<?php
    class cursosController{
        
        /**
         * Mostrar todos los cursos
         */
        public function index(){
            $json = array("status"=>200, "detalles"=>"Se estan mostrando todos los cursos");
            print json_encode($json, true);
        }
        /**
         * Creacion de un registro
         */

        public function create(){
            $json = array("status"=>404, "detalles"=>"El registro se ha guardado satisfactoriamente");
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