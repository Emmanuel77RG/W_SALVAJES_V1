<?php
header("Content-Type: application/json");

// Incluye la conexión
require_once "conexion.php";
$conexionObj = new Conexion();
$conexion = $conexionObj->conectar(); // Obtenemos la conexión PDO

// Verifica que $conexion esté definido
if (!$conexion) {
    echo json_encode(["error" => "Error de conexión a la base de datos."]);
    exit;
}

// Obtener datos del cuerpo del request
$data = json_decode(file_get_contents("php://input"), true);

// Validar datos
if (!isset($data['fecha'], $data['hora'], $data['productos'], $data['total'])) {
    echo json_encode(["error" => "Datos incompletos."]);
    exit;
}

$fecha = $data['fecha'];
$hora = $data['hora'];
// Aquí utilizamos JSON_UNESCAPED_UNICODE para no escapar los caracteres especiales
$productos = json_encode($data['productos'], JSON_UNESCAPED_UNICODE);
$total = floatval($data['total']);

// Preparar e insertar
try {
    $stmt = $conexion->prepare("INSERT INTO ventas (fecha_venta, hora_venta, productos_venta, total_venta)
                                 VALUES (:fecha, :hora, :productos, :total)");

    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':hora', $hora);
    $stmt->bindParam(':productos', $productos);
    $stmt->bindParam(':total', $total);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Venta registrada correctamente."]);
    } else {
        echo json_encode(["error" => "Error al registrar la venta."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error de ejecución: " . $e->getMessage()]);
}
?>
