
<?php require_once __DIR__ . '/../config/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaludUSM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <?php if (file_exists(__DIR__ . '/../../CSS/style.css')): ?>
        <link href="<?= BASE_URL ?>/../CSS/style.css" rel="stylesheet">
    <?php endif; ?>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">
            <i class="bi bi-hospital"></i> SaludUSM
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (estaLogueado()): ?>
                    <?php if (tieneRol('paciente')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/paciente/dashboard.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/paciente/mis_citas.php">Mis Citas</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/paciente/agendar.php">Agendar Hora</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/paciente/historial.php">Historial</a></li>
                    <?php elseif (tieneRol('medico')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/medico/dashboard.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/medico/agenda.php">Mi Agenda</a></li>
                    <?php elseif (tieneRol('admin')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Panel</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/centros.php">Centros</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/medicos.php">Médicos</a></li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['nombre']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small"><?= htmlspecialchars($_SESSION['email']) ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (tieneRol('paciente')): ?>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/paciente/perfil.php">Mi Perfil</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login.php">Iniciar Sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/registro.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">