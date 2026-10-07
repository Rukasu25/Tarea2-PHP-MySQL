<?php
require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();

// Obtener id_paciente
$stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE email = ?");
$stmt->execute([$u['email']]);
$id_paciente = (int)$stmt->fetchColumn();

header_html('Inicio - Paciente');
?>

<div class="card shadow mb-4 border-start border-4 border-primary">
    <div class="card-body">
        <h2 class="mb-1">Bienvenido/a, <?= e($u['nombre'] . ' ' . $u['apellido']) ?></h2>
        <p class="text-muted mb-0"><i class="bi bi-person-circle"></i> Rol: <strong>Paciente</strong></p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <i class="bi bi-calendar-plus fs-1 text-primary"></i>
                <h5 class="mt-3">Agendar Cita</h5>
                <p class="text-muted small">Reserva una nueva hora médica.</p>
                <a href="agendar.php" class="btn btn-primary">Abrir agenda</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <i class="bi bi-calendar-check fs-1 text-success"></i>
                <h5 class="mt-3">Mis Citas</h5>
                <p class="text-muted small">Revisa o cancela tus horas.</p>
                <a href="mis_citas.php" class="btn btn-success">Consultar citas</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <i class="bi bi-file-medical fs-1 text-info"></i>
                <h5 class="mt-3">Historial Médico</h5>
                <p class="text-muted small">Revisa tus atenciones anteriores.</p>
                <a href="historial.php" class="btn btn-info text-white">Ver historial</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <i class="bi bi-person-circle fs-1 text-secondary"></i>
                <h5 class="mt-3">Mi Perfil</h5>
                <p class="text-muted small">Actualiza tus datos de contacto.</p>
                <a href="perfil.php" class="btn btn-secondary">Editar perfil</a>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>