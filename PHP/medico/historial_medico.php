<?php
require_once __DIR__ . '/../includes/common.php'; require_role('MEDICO'); $u=user(); $rut=trim($_GET['rut']??''); $rows=[];
if($rut!==''){
    $q=db()->prepare("SELECT a.*,v.fecha_hora,v.paciente,v.medico,v.especialidad,v.centro
    FROM atencion a JOIN vw_citas_detalle v ON v.id_cita=a.id_cita
    WHERE v.id_medico=? AND v.rut_paciente=? ORDER BY v.fecha_hora DESC");
    $q->execute([$u['id_usuario'],$rut]);$rows=$q->fetchAll();
}
header_html('Historial de paciente');
?>
<div class="card p-4"><h2>Historial de pacientes</h2>
<form class="row g-2 mb-3"><div class="col-md-6"><input name="rut" class="form-control" value="<?=e($rut)?>" placeholder="RUT del paciente"></div><div class="col-auto"><button class="btn btn-primary">Buscar</button></div></form>
<?php foreach($rows as $r):?><div class="border rounded p-3 mb-3"><b><?=e($r['fecha_hora'])?></b> - <?=e($r['especialidad'])?> - <?=e($r['centro'])?><br>
<b>Motivo:</b> <?=e($r['motivo'])?><br><b>Observaciones:</b> <?=e($r['observaciones'])?><br>
<b>Diagnósticos:</b> <?php $d=db()->prepare("SELECT d.codigo_cie10,d.descripcion FROM diagnostico_atencion da JOIN diagnostico d ON d.id_diagnostico=da.id_diagnostico WHERE da.id_atencion=?");$d->execute([$r['id_atencion']]);foreach($d as $x)echo e($x['codigo_cie10'].' '.$x['descripcion']).' ';?></div><?php endforeach;?>
</div>
<?php footer_html(); ?>
