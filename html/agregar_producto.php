<?php
header("Content-Type: application/json");

// Incluir conexión
require_once "conexion.php";
$conexionObj = new Conexion();
$conexion = $conexionObj->conectar(); // Obtenemos conexión PDO

if (!$conexion) {
    echo json_encode(["error" => "Error de conexión a la base de datos."]);
    exit;
}

// Obtener datos del cuerpo del request
$data = json_decode(file_get_contents("php://input"), true);

// Validar que todos los campos estén presentes (excepto costo_entrada, que se calcula)
if (!isset($data['id_producto'], $data['nombre_producto'], $data['pz_gr'], $data['costo_unitario'])) {
    echo json_encode(["error" => "Datos incompletos."]);
    exit;
}

// Sanitizar y preparar variables
$id_producto = $data['id_producto'];
$nombre_producto = $data['nombre_producto'];
$pz_gr = floatval($data['pz_gr']);
$costo_unitario = floatval($data['costo_unitario']);
$costo_entrada = $pz_gr * $costo_unitario; // Aquí calculamos el costo total

// Insertar en la base de datos
try {
    $stmt = $conexion->prepare("INSERT INTO inventario (id_producto, nombre_producto, pz_gr, costo_unitario, costo_entrada)
                                 VALUES (:id_producto, :nombre_producto, :pz_gr, :costo_unitario, :costo_entrada)");

    $stmt->bindParam(':id_producto', $id_producto);
    $stmt->bindParam(':nombre_producto', $nombre_producto);
    $stmt->bindParam(':pz_gr', $pz_gr);
    $stmt->bindParam(':costo_unitario', $costo_unitario);
    $stmt->bindParam(':costo_entrada', $costo_entrada);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Producto agregado al inventario."]);
    } else {
        echo json_encode(["error" => "Error al registrar el producto."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error de ejecución: " . $e->getMessage()]);
}
?>
