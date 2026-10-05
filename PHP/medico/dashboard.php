<?php require_once __DIR__ . '/../includes/common.php'; require_role('MEDICO'); $u=user(); header_html('Inicio'); ?>
<h1>Bienvenido/a, <?=e($u['nombre'])?></h1><p class="lead">Rol: <?=e($u['rol'])?></p>
<div class="row g-3">
<div class="col-md-6"><div class="card p-3"><h4>Agenda</h4><a href="agenda.php" class="btn btn-primary">Abrir agenda</a></div></div>
<div class="col-md-6"><div class="card p-3"><h4>Historial de pacientes</h4><a href="historial_medico.php" class="btn btn-primary">Consultar</a></div></div>
<div class="col-md-6"><div class="card p-3"><h4>Mi perfil</h4><a href="perfil.php" class="btn btn-primary">Editar perfil</a></div></div>
</div>
<?php footer_html(); ?>
