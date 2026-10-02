<?php
require_once __DIR__ . '/../includes/common.php'; require_role('PACIENTE');
$u=user(); $error='';
$esp=db()->query("SELECT * FROM especialidad ORDER BY nombre")->fetchAll();
$cent=db()->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll();
$med=db()->query("SELECT DISTINCT u.id_usuario, CONCAT(u.nombre,' ',u.apellido) nombre FROM usuario u JOIN medico m ON m.id_medico=u.id_usuario ORDER BY nombre")->fetchAll();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $idmed=(int)$_POST['id_medico']; $idesp=(int)$_POST['id_especialidad']; $idcent=(int)$_POST['id_centro'];
    $fecha=$_POST['fecha']; $hora=$_POST['hora'];
    $dt="$fecha $hora:00";
    try{
        $s=db()->prepare("INSERT INTO cita(id_paciente,id_medico,id_especialidad,id_centro,fecha_hora,id_estado)
        VALUES(?,?,?,?,?,(SELECT id_estado FROM estado_cita WHERE nombre='Reservada'))");
        $s->execute([$u['id_usuario'],$idmed,$idesp,$idcent,$dt]);
        flash('Cita creada correctamente.'); redirect('mis_citas.php');
    }catch(Throwable $e){ $error=$e->getMessage(); }
}
header_html('Agendar hora');
?>
<div class="card p-4"><h2>Agendar hora</h2>
<p>Supuesto de esta implementación: bloques de 30 minutos, entre 08:00 y 17:30 de lunes a viernes.</p>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post" class="row g-3">
<div class="col-md-3"><label class="form-label">Especialidad</label><select name="id_especialidad" class="form-select" required><?php foreach($esp as $x):?><option value="<?=$x['id_especialidad']?>"><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-3"><label class="form-label">Centro</label><select name="id_centro" class="form-select" required><?php foreach($cent as $x):?><option value="<?=$x['id_centro']?>"><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-3"><label class="form-label">Medico</label><select name="id_medico" class="form-select" required><?php foreach($med as $x):?><option value="<?=$x['id_usuario']?>"><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label class="form-label">Fecha</label><input type="date" name="fecha" class="form-control" required></div>
<div class="col-md-1"><label class="form-label">Hora</label><input type="time" name="hora" min="08:00" max="17:30" step="1800" class="form-control" required></div>
<div class="col-12"><button class="btn btn-primary">Reservar</button></div>
</form></div>
<?php footer_html(); ?>
