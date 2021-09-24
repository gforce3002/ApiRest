<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Cursos;
use App\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //para realizar validaciones
use Illuminate\Support\Facades\DB;

class CursosController extends Controller
{
    /**
     * Function para obtener todos los cursos de la base de datos
     */
    public function index(Request $request){
        $token = $request->header('Authorization');
        $acounts = explode(":",base64_decode(str_replace("Basic ","",$token)));
        $json = array();
        
        
        $clientes = Clientes::all();
        

        foreach ($clientes as $key => $value) {
            if(strcmp($acounts[0],$value["idCliente"])==0 && strcmp($acounts[1],$value["secret_key"]==0)){
                //$cursos = Cursos::all();
                $cursos = DB::table('Cursos')
                ->join('clientes','Cursos.id_creador',"=","clientes.id")
                ->select("Cursos.id", "Cursos.titulo", "Cursos.descripcion", "Cursos.instructor","Cursos.imagen", "Cursos.precio","clientes.nombres","clientes.apellidos","clientes.email")
                ->get();
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

    /**
     * Funcion para crear cursos
     */
    public function store(Request $request){
        $token = $request->header('Authorization');
        $acounts = explode(":",base64_decode(str_replace("Basic ","",$token)));
        $json = array("estatus"=>"404","detalles"=>"No encontrado");
        $clientes = Clientes::all();
         foreach ($clientes as $key => $value) {
            if(strcmp($acounts[0],$value["idCliente"])==0 && strcmp($acounts[1],$value["secret_key"]==0)){

                
                $datos = array("titulo"=> $request->input("titulo"),"descripcion"=> $request->input("descripcion"), 
                "instructor"=> $request->input("instructor"), "imagen"=>$request->input("imagen"),"precio"=>$request->input("precio"));
                
                if(!empty($datos)){
                    //Validar datos
                    $validator = Validator::make($datos,[
                        'titulo'=>'required|string|max:255|unique:Cursos',
                        'descripcion'=>'required|string|max:255|unique:Cursos',
                        'instructor'=>'required|string|max:255',
                        'imagen'=>'required|string|max:255|unique:Cursos',
                        'precio'=>'required|numeric'

                    ]); 

                    if($validator->fails()){
                        $json = array("status"=>404, "detalles"=>"Registro con errores: los campos titulo, descripcion y nombre ya existen en la base de datos");    
                    }else{
                        $cursos = new Cursos();
                        $cursos->titulo = $datos["titulo"];
                        $cursos->descripcion = $datos["descripcion"];
                        $cursos->instructor = $datos["instructor"];
                        $cursos->imagen = $datos["imagen"];
                        $cursos->precio = $datos["precio"];
                        $cursos->id_creador = $value["id"];
                        $cursos->save();
                        $json = array("status"=>200, "detalles"=>"Su curso ha sido guardado");
                    }
                }else{
                    $json = array("status"=>404, "detalles"=>"Los registros no pueden estar vacios");
                }
                break; 
            }
        } 
        return json_encode($json, true);
    }
    /**
     * funcion para obtener informacion de un
     */
    public function show($id, Request $request){
        $token = $request->header('Authorization');
        $acounts = explode(":",base64_decode(str_replace("Basic ","",$token)));
        $json = array("estatus"=>"404","detalles"=>"No encontrado");
        $clientes = Clientes::all();
        foreach ($clientes as $key => $value) {
            if(strcmp($acounts[0],$value["idCliente"])==0 && strcmp($acounts[1],$value["secret_key"]==0)){
                //$getCurso = Cursos::where("id",$id)->get();
                $getCurso = DB::table('Cursos')->find($id);
                //var_dump($getCurso);
                if($value["id"] == $getCurso->id_creador){
                    $json = array("status"=>200, "detalles"=>$getCurso);
                }else{
                    $json = array("status"=>404, "detalles"=>"No esta autorizado para modificar este curso");
                }
                break;
            }
        }
        return json_encode($json, true);
    }


    /**
     * Funcion para Ediatar un registro
     */
    public function update($id, Request $request){
        $token = $request->header('Authorization');
        $acounts = explode(":",base64_decode(str_replace("Basic ","",$token)));
        $json = array("estatus"=>"404","detalles"=>"No encontrado");
        $clientes = Clientes::all();
         foreach ($clientes as $key => $value) {
            if(strcmp($acounts[0],$value["idCliente"])==0 && strcmp($acounts[1],$value["secret_key"]==0)){
                $datos = array("titulo"=> $request->input("titulo"),"descripcion"=> $request->input("descripcion"), 
                "instructor"=> $request->input("instructor"), "imagen"=>$request->input("imagen"),"precio"=>$request->input("precio"));
                if(!empty($datos)){
                    //Validar datos
                    $validator = Validator::make($datos,[
                        'titulo'=>'required|string|max:255',
                        'descripcion'=>'required|string|max:255',
                        'instructor'=>'required|string|max:255',
                        'imagen'=>'required|string|max:255',
                        'precio'=>'required|numeric'

                    ]); 

                    if($validator->fails()){
                        $json = array("status"=>404, "detalles"=>"Registro con errores: No se permiten caracteres especiales");    
                    }else{
                        $getCurso = Cursos::where("id",$id)->get();
                        if($value["id"] == $getCurso[0]["id_creador"]){
                            Cursos::where("id",$id)->update($datos);
                            $json = array("status"=>200, "detalles"=>"Su curso ha sido Actualizado");
                        }else{
                            $json = array("status"=>404, "detalles"=>"No esta autorizado para modificar este curso");
                        }
                        
                    }
                }else{
                    $json = array("status"=>404, "detalles"=>"Los registros no pueden estar vacios");
                }
                break; 
            }
        } 
        return json_encode($json, true);
    }

    /**
     * Funcion para eliminar un registro de la base de datos
     */
    public function destroy($id, Request $request){
        $token = $request->header('Authorization');
        $acounts = explode(":",base64_decode(str_replace("Basic ","",$token)));
        $json = array("estatus"=>"404","detalles"=>"No encontrado");
        $clientes = Clientes::all();
        foreach ($clientes as $key => $value) {
            if(strcmp($acounts[0],$value["idCliente"])==0 && strcmp($acounts[1],$value["secret_key"]==0)){
                $getCurso = Cursos::where("id",$id)->get();
                if($value["id"] == $getCurso[0]["id_creador"]){
                    $getCurso = Cursos::where("id",$id)->delete();
                    $json = array("status"=>200, "detalles"=>"Registro Eliminado");
                }else{
                    $json = array("status"=>404, "detalles"=>"No esta autorizado para modificar este curso");
                }
            }
        }

        return json_encode($json,true);
    }
}
