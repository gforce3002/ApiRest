<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cursos;

class CursosController extends Controller
{
    /**
     * Function para obtener todos los cursos de la base de datos
     */
    public function index(){
        $cursos = Cursos::all();
        if(!empty($cursos)){
            $json = array("status"=>200, "total_registros"=>count($cursos), "data"=>$cursos);
            
        }else{
            $json = array("status"=>200, "detalles"=>"No hay cursos registrados");
        }

        return json_encode($json, true);
    }
}
