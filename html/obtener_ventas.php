<?php
require 'variables.php';

$conexion = new mysqli(host, user, password, BD);

if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Establecer la codificación UTF-8
$conexion->set_charset("utf8");

$sql = "SELECT id_ventas, fecha_venta, hora_venta, productos_venta, total_venta FROM ventas ORDER BY id_ventas DESC";
$resultado = $conexion->query($sql);

$ventas = [];

while ($fila = $resultado->fetch_assoc()) {
  $ventas[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($ventas);

$conexion->close();
?>
