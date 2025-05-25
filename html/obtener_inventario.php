<?php
header("Content-Type: application/json");

// Incluir la conexión PDO centralizada
require_once "conexion.php";
$conexionObj = new Conexion();
$conexion = $conexionObj->conectar();

if (!$conexion) {
    echo json_encode(["error" => "Error de conexión a la base de datos."]);
    exit;
}

// Consulta directa sin agrupar
$sql = "
    SELECT 
        id_producto,
        nombre_producto,
        pz_gr,
        costo_unitario
    FROM inventario
";

try {
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultado);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
}
?>
