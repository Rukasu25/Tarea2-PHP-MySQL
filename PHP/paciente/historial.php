<?php
require_once __DIR__ . '/../includes/common.php'; require_role('PACIENTE'); $u=user();
$s=$pdo->prepare("SELECT a.*,c.fecha_hora,v.medico,v.especialidad,v.centro
FROM atencion a JOIN cita c ON c.id_cita=a.id_cita JOIN vw_citas_detalle v ON v.id_cita=c.id_cita
WHERE c.id_paciente=? ORDER BY c.fecha_hora DESC"); $s->execute([$u['id_usuario']]); $rows=$s->fetchAll();
header_html('Historial');
?>
<h2>Historial clinico</h2>
<?php foreach($rows as $r):?><div class="card p-3 mb-3"><h5><?=e($r['fecha_hora'])?> - <?=e($r['especialidad'])?></h5>
<p><b>Medico:</b> <?=e($r['medico'])?> | <b>Centro:</b> <?=e($r['centro'])?></p><p><b>Motivo:</b> <?=e($r['motivo'])?></p><p><b>Observaciones:</b> <?=e($r['observaciones'])?></p>
<?php $d=$pdo->prepare("SELECT d.codigo_cie10,d.descripcion FROM diagnostico_atencion da JOIN diagnostico d ON d.id_diagnostico=da.id_diagnostico WHERE da.id_atencion=?");$d->execute([$r['id_atencion']]);?>
<p><b>Diagnosticos:</b> <?php foreach($d as $x) echo e($x['codigo_cie10'].' '.$x['descripcion']).' ';?></p>
<?php $rx=$pdo->prepare("SELECT m.nombre,r.dosis,r.dias_tratamiento FROM receta_linea r JOIN medicamento m ON m.id_medicamento=r.id_medicamento WHERE r.id_atencion=?");$rx->execute([$r['id_atencion']]);?>
<p><b>Receta:</b> <?php foreach($rx as $x) echo e($x['nombre'].' - '.$x['dosis'].' - '.$x['dias_tratamiento'].' dias').' ';?></p>
</div><?php endforeach;?>
<?php footer_html(); ?>
