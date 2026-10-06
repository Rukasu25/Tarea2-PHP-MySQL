<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/common.php';

requiereRol('paciente');

$stmt = $pdo->prepare("SELECT Nombre FROM Paciente WHERE Rut_Paciente = ?");
$stmt->execute([$_SESSION['rut']]);
$paciente = $stmt->fetch();

header_html('Inicio Paciente'); 
?>

<div class="container py-4">
    <h1 class="mb-3 text-primary fw-bold">Bienvenido/a, <?= htmlspecialchars($paciente['Nombre']) ?></h1>
    <p class="lead mb-4">Panel de control - Paciente</p>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0 text-center h-100">
                <h4 class="fw-bold">Agendar Cita</h4>
                <p class="text-muted">Reserva una nueva hora médica.</p>
                <a href="agendar.php" class="btn btn-primary mt-auto py-2">Abrir agenda</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0 text-center h-100">
                <h4 class="fw-bold">Mis Citas</h4>
                <p class="text-muted">Revisa o cancela tus horas programadas.</p>
                <a href="mis_citas.php" class="btn btn-primary mt-auto py-2">Consultar citas</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0 text-center h-100">
                <h4 class="fw-bold">Historial Médico</h4>
                <p class="text-muted">Revisa tus atenciones anteriores.</p>
                <a href="historial.php" class="btn btn-primary mt-auto py-2">Ver historial</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0 text-center h-100">
                <h4 class="fw-bold">Mi Perfil</h4>
                <p class="text-muted">Actualiza tus datos de contacto.</p>
                <a href="perfil.php" class="btn btn-primary mt-auto py-2">Editar perfil</a>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>