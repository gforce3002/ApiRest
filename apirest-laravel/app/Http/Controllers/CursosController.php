<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cursos;
use App\Clientes;

class CursosController extends Controller
{
    /**
     * Function para obtener todos los cursos de la base de datos
     */
    public function index(Request $request){
        $token = $request->header('Authorization');
        $acounts = explode(":",base64_decode(str_replace("Basic ","",$token)));
        
        
        
        $clientes = Clientes::all();
        foreach ($clientes as $key => $value) {
            if(strcmp($acounts[0],$value["idCliente"])==0 && strcmp($acounts[1],$value["secret_key"]==0)){
                $cursos = Cursos::all();
                if(!empty($cursos)){
                    $json = array("status"=>200, "total_registros"=>count($cursos), "data"=>$cursos);    
                }else{
                    $json = array("status"=>200, "detalles"=>"No hay cursos registrados");
                }
                break;
            }
        }
        return json_encode($json, true);
        
        
    }
}
