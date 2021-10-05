<?php

    class conexion{
        static public function conectar(){
            $link = new PDO("mysql:host=localhost; dbname=apirest_udemy","root","9804520k");
            $link->exec("set names utf8");
            return $link;
        }
    }