<?php
require_once __DIR__ . '/../includes/common.php'; require_role('PACIENTE'); $u=user(); $id=(int)($_GET['id']??0); $error='';
$s=db()->prepare("SELECT * FROM vw_citas_detalle WHERE id_cita=? AND id_paciente=?");$s->execute([$id,$u['id_usuario']]);$c=$s->fetch();
if(!$c || strtotime($c['fecha_hora'])<=time() || !in_array($c['estado'],['Reservada','Confirmada'],true)) exit('Esta cita no puede reprogramarse.');
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        $dt=$_POST['fecha'].' '.$_POST['hora'].':00';
        $q=db()->prepare("UPDATE cita SET fecha_hora=? WHERE id_cita=? AND id_paciente=? AND fecha_hora>NOW()
        AND id_estado IN (SELECT id_estado FROM estado_cita WHERE nombre IN ('Reservada','Confirmada'))");
        $q->execute([$dt,$id,$u['id_usuario']]);
        if($q->rowCount()===0) throw new Exception('La cita ya no puede modificarse.');
        flash('Cita reprogramada.'); redirect('mis_citas.php');
    }catch(Throwable $e){$error=$e->getMessage();}
}
header_html('Reprogramar cita');
?>
<div class="card p-4"><h2>Reprogramar cita #<?=$id?></h2>
<p><?=e($c['medico'])?> - <?=e($c['especialidad'])?> - <?=e($c['centro'])?></p>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post" class="row g-3"><div class="col-md-4"><label class="form-label">Nueva fecha</label><input type="date" name="fecha" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Nueva hora</label><input type="time" name="hora" min="08:00" max="17:30" step="1800" class="form-control" required></div>
<div class="col-12"><button class="btn btn-primary">Guardar cambio</button> <a href="mis_citas.php" class="btn btn-secondary">Volver</a></div></form></div>
<?php footer_html(); ?>
