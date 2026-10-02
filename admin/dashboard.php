<?php require_once __DIR__ . '/../includes/common.php'; require_role('ADMIN'); $u=user(); header_html('Inicio'); ?>
<h1>Bienvenido/a, <?=e($u['nombre'])?></h1><p class="lead">Rol: <?=e($u['rol'])?></p>
<div class="row g-3">
<div class="col-md-4"><div class="card p-3"><h4>Panel</h4><a href="admin.php" class="btn btn-primary">Ver panel</a></div></div>
<div class="col-md-4"><div class="card p-3"><h4>Centros</h4><a href="centros.php" class="btn btn-primary">Gestionar</a></div></div>
<div class="col-md-4"><div class="card p-3"><h4>Médicos</h4><a href="medicos.php" class="btn btn-primary">Gestionar</a></div></div>
<div class="col-md-6"><div class="card p-3"><h4>Búsqueda de citas</h4><a href="buscar_citas.php" class="btn btn-primary">Buscar</a></div></div>
<div class="col-md-6"><div class="card p-3"><h4>Mi perfil</h4><a href="perfil.php" class="btn btn-primary">Editar perfil</a></div></div>
</div>
<?php footer_html(); ?>
