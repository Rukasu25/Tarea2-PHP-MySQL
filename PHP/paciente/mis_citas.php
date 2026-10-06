<?php
require_once __DIR__ . '/../includes/common.php'; require_role('PACIENTE');
$pdo->exec("CALL sp_cancelar_citas_vencidas()");
$u=user();
if(isset($_GET['cancelar'])){
    $id=(int)$_GET['cancelar'];
    $st=$pdo->prepare("UPDATE cita SET id_estado=(SELECT id_estado FROM estado_cita WHERE nombre='Cancelada')
    WHERE id_cita=? AND id_paciente=? AND fecha_hora>NOW()
    AND id_estado IN (SELECT id_estado FROM estado_cita WHERE nombre IN ('Reservada','Confirmada'))");
    $st->execute([$id,$u['id_usuario']]); flash($st->rowCount()?'Cita cancelada.':'No se pudo cancelar la cita.'); redirect('mis_citas.php');
}
$st=$pdo->prepare("SELECT * FROM vw_citas_detalle WHERE id_paciente=? ORDER BY fecha_hora DESC");
$st->execute([$u['id_usuario']]); $rows=$st->fetchAll();
header_html('Mis citas');
?>
<h2>Mis citas</h2><a class="btn btn-primary mb-3" href="agendar.php">Nueva cita</a>
<table class="table table-striped"><thead><tr><th>Fecha/hora</th><th>Medico</th><th>Especialidad</th><th>Centro</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=e($r['fecha_hora'])?></td><td><?=e($r['medico'])?></td><td><?=e($r['especialidad'])?></td><td><?=e($r['centro'])?></td><td><?=e($r['estado'])?></td>
<td><?php if(strtotime($r['fecha_hora'])>time() && in_array($r['estado'],['Reservada','Confirmada'],true)):?>
<a class="btn btn-sm btn-warning" href="reprogramar.php?id=<?=$r['id_cita']?>">Reprogramar</a>
<a class="btn btn-sm btn-danger" href="?cancelar=<?=$r['id_cita']?>" onclick="return confirm('¿Cancelar cita?')">Cancelar</a>
<?php endif;?></td></tr><?php endforeach;?>
</tbody></table>
<?php footer_html(); ?>
