<?php
declare(strict_types=1);
define('BASE_URL', '/Tarea2-PHP-MySQL/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// ============================================================
// FUNCIONES DE UTILIDAD
// ============================================================

function e($v): string { 
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); 
}

function redirect(string $url): never { 
    header("Location: $url"); 
    exit; 
}

function flash(?string $msg = null): ?string {
    if ($msg !== null) $_SESSION['flash'] = $msg;
    $x = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $x;
}

function user(): ?array { 
    return $_SESSION['user'] ?? null; 
}

function require_login(): void { 
    if (!user()) redirect(url('login.php')); 
}

function require_role(string ...$roles): void {
    require_login();
    if (!in_array(user()['rol'], $roles, true)) {
        http_response_code(403);
        exit('Acceso denegado');
    }
}

function url(string $path = ''): string { 
    return BASE_URL . '/' . ltrim($path, '/'); 
}

// ============================================================
// HEADER Y FOOTER
// ============================================================

function header_html(string $title): void {
    $u = user();
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>' . e($title) . ' - SaludUSM</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">';
    echo '<link href="' . url('CSS/style.css') . '" rel="stylesheet"></head><body>';

    if ($u) {
        echo '<nav class="navbar navbar-dark bg-primary navbar-expand-lg"><div class="container">';
        echo '<a class="navbar-brand" href="' . url($u['rol'] === 'PACIENTE' ? 'paciente/dashboard.php' : ($u['rol'] === 'MEDICO' ? 'medico/dashboard.php' : 'admin/dashboard.php')) . '">';
        echo '<i class="bi bi-hospital"></i> SaludUSM</a>';
        echo '<div class="navbar-nav ms-auto align-items-center">';

        if ($u['rol'] === 'PACIENTE') {
            echo '<a class="nav-link" href="' . url('paciente/buscar.php') . '">Buscar</a>';
            echo '<a class="nav-link" href="' . url('paciente/mis_citas.php') . '">Mis citas</a>';
            echo '<a class="nav-link" href="' . url('paciente/agendar.php') . '">Agendar</a>';
            echo '<a class="nav-link" href="' . url('paciente/historial.php') . '">Historial</a>';
        }
        if ($u['rol'] === 'MEDICO') {
            echo '<a class="nav-link" href="' . url('medico/agenda.php') . '">Agenda</a>';
            echo '<a class="nav-link" href="' . url('medico/historial_medico.php') . '">Historial pacientes</a>';
        }
        if ($u['rol'] === 'ADMIN') {
            echo '<a class="nav-link" href="' . url('admin/admin.php') . '">Administración</a>';
            echo '<a class="nav-link" href="' . url('admin/centros.php') . '">Centros</a>';
            echo '<a class="nav-link" href="' . url('admin/medicos.php') . '">Médicos</a>';
            echo '<a class="nav-link" href="' . url('admin/buscar_citas.php') . '">Buscar citas</a>';
        }

        echo '<a class="nav-link" href="' . url(($u['rol'] === 'PACIENTE' ? 'paciente/perfil.php' : ($u['rol'] === 'MEDICO' ? 'medico/perfil.php' : 'admin/perfil.php'))) . '">Perfil</a>';
        echo '<span class="navbar-text ms-3 me-2"><i class="bi bi-person-circle"></i> ' . e($u['nombre']) . '</span>';
        echo '<a class="nav-link" href="' . url('logout.php') . '">Salir</a>';
        echo '</div></div></nav>';
    }

    echo '<main class="container py-4">';
    if ($m = flash()) echo '<div class="alert alert-info">' . e($m) . '</div>';
}

function footer_html(): void {
    echo '</main>';
    echo '<footer class="text-center text-muted py-4 mt-5">';
    echo '<small>&copy; 2026 SaludUSM - INF-239 Bases de Datos</small>';
    echo '</footer>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
    echo '</body></html>';
}