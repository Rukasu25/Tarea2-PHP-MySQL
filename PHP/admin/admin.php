<?php
require_once __DIR__ . '/../includes/common.php'; require_role('ADMIN');
$centros=db()->query("SELECT c.nombre,COUNT(ci.id_cita) total,
COALESCE(SUM(CASE WHEN e.nombre='No Asistio' THEN 1 ELSE 0 END),0) no_asistio
FROM centro_medico c LEFT JOIN cita ci ON ci.id_centro=c.id_centro
LEFT JOIN estado_cita e ON e.id_estado=ci.id_estado GROUP BY c.id_centro ORDER BY c.nombre")->fetchAll();
$diag=db()->query("SELECT d.codigo_cie10,d.descripcion,COUNT(da.id_atencion) total FROM diagnostico d
LEFT JOIN diagnostico_atencion da ON da.id_diagnostico=d.id_diagnostico GROUP BY d.id_diagnostico
ORDER BY total DESC,d.codigo_cie10 LIMIT 5")->fetchAll();
header_html('Panel administrador');
?>
<h2>Panel de gestion</h2><div class="card p-3 mb-4"><h4>Citas por centro</h4><table class="table"><tr><th>Centro</th><th>Total</th><th>% inasistencia</th></tr>
<?php foreach($centros as $c):$pct=$c['total']?100*$c['no_asistio']/$c['total']:0;?><tr><td><?=e($c['nombre'])?></td><td><?=$c['total']?></td><td><?=number_format($pct,2)?>%</td></tr><?php endforeach;?></table></div>
<div class="card p-3"><h4>5 diagnosticos mas frecuentes</h4><table class="table"><tr><th>CIE-10</th><th>Descripcion</th><th>Cantidad</th></tr><?php foreach($diag as $d):?><tr><td><?=e($d['codigo_cie10'])?></td><td><?=e($d['descripcion'])?></td><td><?=$d['total']?></td></tr><?php endforeach;?></table></div>
<?php footer_html(); ?>
