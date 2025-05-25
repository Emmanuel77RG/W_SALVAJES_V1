<?php
require_once 'conexion.php';
$conexionBD = new Conexion();
$conn = $conexionBD->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $nombre = $_POST['nombre'];
        $rol = $_POST['rol'];
        $contrasena = $_POST['contrasena'];

        try {
            $sql = "INSERT INTO usuarios (nombre_usuario, rol_usuario, contraseña_usuario)
                    VALUES (:nombre, :rol, :contrasena)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':contrasena', $contrasena);
            $stmt->execute();
            echo json_encode(['status' => 'ok']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    elseif ($accion === 'editar') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $rol = $_POST['rol'];

        try {
            $sql = "UPDATE usuarios SET nombre_usuario = :nombre, rol_usuario = :rol WHERE id_usuario = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(['status' => 'ok']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    elseif ($accion === 'eliminar') {
        $id = $_POST['id'];

        try {
            $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(['status' => 'ok']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    exit;
}

function rolTexto($rol) {
    switch ($rol) {
        case 1: return 'Mesero';
        case 2: return 'Administrador';
        case 3: return 'Super Administrador';
        default: return 'Desconocido';
    }
}

try {
    $sql = "SELECT * FROM usuarios";
    $stmt = $conn->query($sql);
    $usuariosRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $usuarios = array_map(function($usuario) {
        return [
            'id_usuario' => $usuario['id_usuario'],
            'nombre_usuario' => $usuario['nombre_usuario'],
            'rol_usuario' => rolTexto($usuario['rol_usuario'])
        ];
    }, $usuariosRaw);

    header('Content-Type: application/json');
    echo json_encode($usuarios);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
