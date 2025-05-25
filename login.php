<?php
require_once 'conexion.php';

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$conexionBD = new Conexion();
$conn = $conexionBD->conectar();

$sql = "SELECT rol_usuario FROM usuarios WHERE nombre_usuario = :usuario AND contraseña_usuario = :contrasena";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario', $usuario);
$stmt->bindParam(':contrasena', $contrasena);
$stmt->execute();

if ($stmt->rowCount() === 1) {
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    $rol = $datos['rol_usuario'];

    switch ($rol) {
        case 1:
            header("Location: mesero.html");
            break;
        case 2:
            header("Location: administrador.html");
            break;
        case 3:
            header("Location: venta.html"); //  Redirección cambiada aquí
            break;
        default:
            echo "<script>
                alert('Rol no válido.');
                window.location.href = 'login.html';
            </script>";
    }
    exit();
} else {
    echo "<script>
        alert('No se pudo iniciar sesión. Usuario o contraseña incorrectos.');
        window.location.href = 'login.html';
    </script>";
    exit();
}
?>
