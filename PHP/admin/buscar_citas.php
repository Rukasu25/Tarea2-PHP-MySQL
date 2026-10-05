<?php
require_once __DIR__ . '/../includes/common.php'; require_role('ADMIN','MEDICO','PACIENTE'); $u=user();
$where=[];$params=[];
if($u['rol']==='PACIENTE'){ $where[]='v.id_paciente=?';$params[]=$u['id_usuario'];}
if($u['rol']==='MEDICO'){ $where[]='v.id_medico=?';$params[]=$u['id_usuario'];}
foreach(['desde'=>'DATE(v.fecha_hora)>=?','hasta'=>'DATE(v.fecha_hora)<=?','id_centro'=>'v.id_centro=?','id_especialidad'=>'v.id_especialidad=?','id_medico'=>'v.id_medico=?','estado'=>'v.estado=?','region'=>'v.region=?','prevision'=>'v.prevision=?'] as $k=>$cond){
    if(($_GET[$k]??'')!==''){ $where[]=$cond;$params[]=$_GET[$k];}
}
$sql="SELECT v.* FROM vw_citas_detalle v".($where?' WHERE '.implode(' AND ',$where):'')." ORDER BY v.fecha_hora DESC";
$s=db()->prepare($sql);$s->execute($params);$rows=$s->fetchAll();
$cent=db()->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll();$esp=db()->query("SELECT * FROM especialidad ORDER BY nombre")->fetchAll();$med=db()->query("SELECT id_usuario,CONCAT(nombre,' ',apellido) n FROM usuario WHERE rol='MEDICO' ORDER BY n")->fetchAll();
$est=db()->query("SELECT nombre FROM estado_cita ORDER BY nombre")->fetchAll();$reg=db()->query("SELECT DISTINCT region FROM centro_medico ORDER BY region")->fetchAll();$pre=db()->query("SELECT nombre FROM prevision ORDER BY nombre")->fetchAll();
header_html('Busqueda avanzada');
?>
<div class="card p-3"><h2>Busqueda avanzada de citas</h2><form class="row g-2">
<?php foreach([['desde','date','Desde'],['hasta','date','Hasta']] as $x):?><div class="col-md-2"><label><?=$x[2]?></label><input type="<?=$x[1]?>" name="<?=$x[0]?>" value="<?=e($_GET[$x[0]]??'')?>" class="form-control"></div><?php endforeach;?>
<div class="col-md-2"><label>Centro</label><select name="id_centro" class="form-select"><option value="">Todos</option><?php foreach($cent as $x):?><option value="<?=$x['id_centro']?>"><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label>Especialidad</label><select name="id_especialidad" class="form-select"><option value="">Todas</option><?php foreach($esp as $x):?><option value="<?=$x['id_especialidad']?>"><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label>Medico</label><select name="id_medico" class="form-select"><option value="">Todos</option><?php foreach($med as $x):?><option value="<?=$x['id_usuario']?>"><?=e($x['n'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label>Estado</label><select name="estado" class="form-select"><option value="">Todos</option><?php foreach($est as $x):?><option><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label>Region</label><select name="region" class="form-select"><option value="">Todas</option><?php foreach($reg as $x):?><option><?=e($x['region'])?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label>Prevision</label><select name="prevision" class="form-select"><option value="">Todas</option><?php foreach($pre as $x):?><option><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-12"><button class="btn btn-primary">Filtrar</button></div></form></div>
<table class="table table-striped mt-3"><tr><th>Fecha/hora</th><th>Paciente</th><th>Medico</th><th>Especialidad</th><th>Centro</th><th>Estado</th></tr>
<?php foreach($rows as $r):?><tr><td><?=e($r['fecha_hora'])?></td><td><?=e($r['paciente'])?></td><td><?=e($r['medico'])?></td><td><?=e($r['especialidad'])?></td><td><?=e($r['centro'])?></td><td><?=e($r['estado'])?></td></tr><?php endforeach;?></table>
<?php footer_html(); ?>
