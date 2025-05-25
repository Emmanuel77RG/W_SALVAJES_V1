<?php
require_once "variables.php";

class Conexion {
    public function conectar() {
        try {
            $conexion = new PDO("mysql:host=" . host . ";dbname=" . BD, user, password);
            $conexion->exec("set names utf8");
            return $conexion;
        } catch (PDOException $e) {
            // Mostrar el error detallado
            echo "Error de conexiónnnn: " . $e->getMessage();  // Imprimir mensaje de error
            die();
        }
    }
}
?>
