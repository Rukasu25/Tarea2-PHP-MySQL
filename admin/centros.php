<?php
require_once __DIR__ . '/../includes/common.php'; require_role('ADMIN');
if(isset($_GET['del'])){try{db()->prepare("DELETE FROM centro_medico WHERE id_centro=?")->execute([(int)$_GET['del']]);flash('Centro eliminado.');}catch(Throwable $e){flash('No se puede eliminar: '.$e->getMessage());}redirect('centros.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        if(!empty($_POST['id'])) db()->prepare("UPDATE centro_medico SET codigo=?,nombre=?,comuna=?,region=? WHERE id_centro=?")->execute([$_POST['codigo'],$_POST['nombre'],$_POST['comuna'],$_POST['region'],$_POST['id']]);
        else db()->prepare("INSERT INTO centro_medico(codigo,nombre,comuna,region) VALUES(?,?,?,?)")->execute([$_POST['codigo'],$_POST['nombre'],$_POST['comuna'],$_POST['region']]);
        flash('Centro guardado.');
    }catch(Throwable $e){flash($e->getMessage());} redirect('centros.php');
}
$rows=db()->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll(); header_html('Centros');
?>
<h2>Centros medicos</h2><div class="card p-3 mb-4"><form method="post" class="row g-2">
<div class="col"><input name="codigo" class="form-control" placeholder="Codigo" required></div><div class="col"><input name="nombre" class="form-control" placeholder="Nombre" required></div>
<div class="col"><input name="comuna" class="form-control" placeholder="Comuna" required></div><div class="col"><input name="region" class="form-control" placeholder="Region" required></div>
<div class="col-auto"><button class="btn btn-primary">Agregar</button></div></form></div>
<table class="table"><tr><th>Codigo</th><th>Nombre</th><th>Comuna</th><th>Region</th><th></th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['codigo'])?></td><td><?=e($r['nombre'])?></td><td><?=e($r['comuna'])?></td><td><?=e($r['region'])?></td><td><a class="btn btn-sm btn-danger" href="?del=<?=$r['id_centro']?>" onclick="return confirm('Eliminar?')">Eliminar</a></td></tr><?php endforeach;?></table>
<?php footer_html(); ?>
