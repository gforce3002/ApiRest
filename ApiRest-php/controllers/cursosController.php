<?php
    class cursosController{
        /**
         * Creacion de un registro
         */

        public function create(){
            $json = array("status"=>404, "detalles"=>"El registro se ha guardado satisfactoriamente");
            print json_encode($json, true);
        }
    }