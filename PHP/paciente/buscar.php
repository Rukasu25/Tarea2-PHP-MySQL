<?php
require_once __DIR__ . '/../includes/common.php'; require_login();
$q=trim($_GET['q']??'');
$sql="SELECT u.id_usuario, CONCAT(u.nombre,' ',u.apellido) medico,
GROUP_CONCAT(DISTINCT e.nombre ORDER BY e.nombre SEPARATOR ', ') especialidades,
GROUP_CONCAT(DISTINCT CONCAT(c.nombre,' (',c.comuna,')') ORDER BY c.nombre SEPARATOR ', ') centros
FROM usuario u JOIN medico m ON m.id_medico=u.id_usuario
JOIN medico_especialidad me ON me.id_medico=m.id_medico JOIN especialidad e ON e.id_especialidad=me.id_especialidad
JOIN medico_centro mc ON mc.id_medico=m.id_medico JOIN centro_medico c ON c.id_centro=mc.id_centro
WHERE (?='' OR CONCAT(u.nombre,' ',u.apellido) LIKE ? OR e.nombre LIKE ?)
GROUP BY u.id_usuario ORDER BY medico";
$st=$pdo->prepare($sql); $like="%$q%"; $st->execute([$q,$like,$like]); $rows=$st->fetchAll();
header_html('Buscar médicos');
?>
<div class="card p-4"><h2>Buscar médicos</h2><form class="row g-2 mb-3">
<div class="col"><input name="q" class="form-control" value="<?=e($q)?>" placeholder="Nombre o especialidad"></div>
<div class="col-auto"><button class="btn btn-primary">Buscar</button></div></form>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Medico</th><th>Especialidades</th><th>Centros</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=e($r['medico'])?></td><td><?=e($r['especialidades'])?></td><td><?=e($r['centros'])?></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php footer_html(); ?>
