<?php
require_once __DIR__ . '/../includes/common.php'; require_role('MEDICO'); $u=user();
$fecha=$_GET['fecha']??date('Y-m-d');
if(isset($_POST['id_cita'],$_POST['estado'])){
    $permitidas=['Confirmada','Atendida','No Asistio'];
    if(in_array($_POST['estado'],$permitidas,true)){
        $s=db()->prepare("UPDATE cita SET id_estado=(SELECT id_estado FROM estado_cita WHERE nombre=?)
        WHERE id_cita=? AND id_medico=?");
        $s->execute([$_POST['estado'],(int)$_POST['id_cita'],$u['id_usuario']]); flash('Estado actualizado.');
    } redirect('agenda.php?fecha='.urlencode($fecha));
}
$s=db()->prepare("SELECT * FROM vw_citas_detalle WHERE id_medico=? AND DATE(fecha_hora)=? ORDER BY fecha_hora");
$s->execute([$u['id_usuario'],$fecha]); $rows=$s->fetchAll();
header_html('Agenda medica');
?>
<div class="card p-4"><h2>Agenda</h2><form class="mb-3"><input type="date" name="fecha" value="<?=e($fecha)?>" class="form-control" onchange="this.form.submit()"></form>
<table class="table table-striped"><thead><tr><th>Hora</th><th>Paciente</th><th>Prevision</th><th>Centro</th><th>Estado</th><th></th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=date('H:i',strtotime($r['fecha_hora']))?></td><td><?=e($r['paciente'])?></td><td><?=e($r['prevision'])?></td><td><?=e($r['centro'])?></td><td><?=e($r['estado'])?></td>
<td><form method="post" class="d-flex gap-1"><input type="hidden" name="id_cita" value="<?=$r['id_cita']?>">
<select name="estado" class="form-select form-select-sm"><option>Confirmada</option><option>Atendida</option><option>No Asistio</option></select><button class="btn btn-sm btn-primary">Guardar</button>
<?php if($r['estado']==='Atendida'):?><a class="btn btn-sm btn-success" href="atencion.php?id=<?=$r['id_cita']?>">Atencion</a><?php endif;?></form></td></tr><?php endforeach;?>
</tbody></table></div>
<?php footer_html(); ?>
