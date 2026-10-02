<?php
require_once __DIR__ . '/../includes/common.php'; require_role('ADMIN'); $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        db()->beginTransaction();
        $rut=$_POST['rut'];$nom=$_POST['nombre'];$ape=$_POST['apellido'];$email=$_POST['email'];$pass=$_POST['password'];
        db()->prepare("INSERT INTO usuario(rut,nombre,apellido,email,password_hash,rol) VALUES(?,?,?,?,?,'MEDICO')")
          ->execute([$rut,$nom,$ape,$email,password_hash($pass,PASSWORD_DEFAULT)]);
        $id=(int)db()->lastInsertId(); db()->prepare("INSERT INTO medico VALUES(?)")->execute([$id]);
        foreach(array_slice($_POST['especialidades']??[],0,3) as $x) db()->prepare("INSERT INTO medico_especialidad VALUES(?,?)")->execute([$id,(int)$x]);
        foreach(array_unique($_POST['centros']??[]) as $x) db()->prepare("INSERT INTO medico_centro VALUES(?,?)")->execute([$id,(int)$x]);
        db()->commit(); flash('Medico creado.');
    }catch(Throwable $e){if(db()->inTransaction())db()->rollBack();$error=$e->getMessage();}
}
$esp=db()->query("SELECT * FROM especialidad ORDER BY nombre")->fetchAll();$cent=db()->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll();
$rows=db()->query("SELECT u.id_usuario,CONCAT(u.nombre,' ',u.apellido) medico,u.rut,u.email,
GROUP_CONCAT(DISTINCT e.nombre SEPARATOR ', ') esp,GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') centros
FROM usuario u JOIN medico m ON m.id_medico=u.id_usuario LEFT JOIN medico_especialidad me ON me.id_medico=m.id_medico
LEFT JOIN especialidad e ON e.id_especialidad=me.id_especialidad LEFT JOIN medico_centro mc ON mc.id_medico=m.id_medico
LEFT JOIN centro_medico c ON c.id_centro=mc.id_centro GROUP BY u.id_usuario ORDER BY medico")->fetchAll();
header_html('Medicos');
?>
<h2>Medicos</h2><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<div class="card p-3 mb-4"><form method="post" class="row g-2">
<input class="form-control col" name="rut" placeholder="RUT" required><input class="form-control col" name="nombre" placeholder="Nombre" required><input class="form-control col" name="apellido" placeholder="Apellido" required>
<input class="form-control col" type="email" name="email" placeholder="Email" required><input class="form-control col" type="password" name="password" placeholder="Password" required>
<div class="col-md-6"><label>Especialidades (1 a 3)</label><select name="especialidades[]" multiple class="form-select"><?php foreach($esp as $x):?><option value="<?=$x['id_especialidad']?>"><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-6"><label>Centros (al menos 1)</label><select name="centros[]" multiple class="form-select"><?php foreach($cent as $x):?><option value="<?=$x['id_centro']?>"><?=e($x['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-12"><button class="btn btn-primary">Crear medico</button></div></form></div>
<table class="table"><tr><th>Medico</th><th>RUT</th><th>Email</th><th>Especialidades</th><th>Centros</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['medico'])?></td><td><?=e($r['rut'])?></td><td><?=e($r['email'])?></td><td><?=e($r['esp'])?></td><td><?=e($r['centros'])?></td></tr><?php endforeach;?></table>
<?php footer_html(); ?>
