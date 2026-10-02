<?php require_once __DIR__ . '/../includes/common.php'; require_login(); $u=user(); header_html('Inicio'); ?>
<h1>Bienvenido/a, <?=e($u['nombre'])?></h1>
<p class="lead">Rol: <?=e($u['rol'])?></p>
<div class="row g-3">
<div class="col-md-4"><div class="card p-3"><h4>Mis citas</h4><a href="mis_citas.php" class="btn btn-primary">Ver citas</a></div></div>
<div class="col-md-4"><div class="card p-3"><h4>Agendar hora</h4><a href="agendar.php" class="btn btn-primary">Agendar</a></div></div>
<div class="col-md-4"><div class="card p-3"><h4>Historial</h4><a href="historial.php" class="btn btn-primary">Ver historial</a></div></div>
<div class="col-md-6"><div class="card p-3"><h4>Buscar médico</h4><a href="buscar.php" class="btn btn-primary">Buscar</a></div></div>
<div class="col-md-6"><div class="card p-3"><h4>Mi perfil</h4><a href="perfil.php" class="btn btn-primary">Editar perfil</a></div></div>
</div>
<?php footer_html(); ?>
