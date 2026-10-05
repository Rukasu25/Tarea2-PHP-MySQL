<?php
// ============================================================
// CONEXIÓN A LA BASE DE DATOS
// ============================================================

$host = 'localhost';
$dbname = 'SALUD_USM';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// FUNCIONES DE UTILIDAD
// ============================================================

function estaLogueado() {
    return isset($_SESSION['email']);
}

function tieneRol($rol) {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === $rol;
}

function requiereLogin() {
    if (!estaLogueado()) {
        header('Location: /Saludusm/login.php');
        exit;
    }
}

function requiereRol($rol) {
    requiereLogin();
    if (!tieneRol($rol)) {
        header('Location: /Saludusm/index.php');
        exit;
    }
}

function mostrarMensaje() {
    if (isset($_SESSION['mensaje'])) {
        $tipo = $_SESSION['mensaje_tipo'] ?? 'info';
        echo "<div class='alert alert-$tipo alert-dismissible fade show'>";
        echo htmlspecialchars($_SESSION['mensaje']);
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
        echo "</div>";
        unset($_SESSION['mensaje'], $_SESSION['mensaje_tipo']);
    }
}

function redirigirSegunRol() {
    if (!estaLogueado()) {
        header('Location: /Saludusm/login.php');
        exit;
    }
    switch ($_SESSION['rol']) {
        case 'paciente': header('Location: /Saludusm/paciente/dashboard.php'); break;
        case 'medico':   header('Location: /Saludusm/medico/dashboard.php');   break;
        case 'admin':    header('Location: /Saludusm/admin/dashboard.php');    break;
        default:         header('Location: /Saludusm/login.php');
    }
    exit;
}
?>