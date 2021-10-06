<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    require_once "controllers/routesController.php";
    require_once "controllers/clientesController.php";
    require_once "controllers/cursosController.php";

    require_once "models/clientesModels.php";
    require_once "models/cursosModels.php";

    require_once "auth/Auth.php";

    $rutas = new routesController();
    $rutas->index();