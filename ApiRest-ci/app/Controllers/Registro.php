<?php

namespace App\Controllers;

class Registro extends BaseController{
    public function index(){
        $json = array("detalles:"=> "Detalles no encontrados");
         return json_encode($json, true);
    }
}