<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Iniciamos la session
session_start();

class Config {
    const BBDD_HOST = "192.168.0.210";
    const BBDD_USUARIO = "tomasbarrientos"; //root
    const BBDD_CLAVE = "TM45.9786?"; // vacio
    const BBDD_NOMBRE = "tomasbarrientos"; //abmventas
}

?>
