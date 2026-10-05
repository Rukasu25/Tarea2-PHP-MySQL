<?php
require_once __DIR__ . '/../includes/common.php'; require_login(); $u=user(); $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $nombre=trim($_POST['nombre']);$apellido=trim($_POST['apellido']);$telefono=trim($_POST['telefono']);$pass=$_POST['password']??'';
    try{
        if($pass!==''){
            if(strlen($pass)<10 || !preg_match('/[A-Z]/',$pass)||!preg_match('/[a-z]/',$pass)||!preg_match('/\d/',$pass)) throw new Exception('Nueva contraseña no cumple requisitos.');
            db()->prepare("UPDATE usuario SET nombre=?,apellido=?,telefono=?,password_hash=? WHERE id_usuario=?")
            ->execute([$nombre,$apellido,$telefono,password_hash($pass,PASSWORD_DEFAULT),$u['id_usuario']]);
        }else db()->prepare("UPDATE usuario SET nombre=?,apellido=?,telefono=? WHERE id_usuario=?")->execute([$nombre,$apellido,$telefono,$u['id_usuario']]);
        $_SESSION['user']=db()->query("SELECT * FROM usuario WHERE id_usuario=".(int)$u['id_usuario'])->fetch(); flash('Datos actualizados.'); redirect('perfil.php');
    }catch(Throwable $e){$error=$e->getMessage();}
}
header_html('Perfil');
?>
<div class="card p-4"><h2>Mis datos</h2><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post" class="row g-3"><div class="col-md-6"><label class="form-label">Nombre</label><input name="nombre" class="form-control" value="<?=e($u['nombre'])?>"></div>
<div class="col-md-6"><label class="form-label">Apellido</label><input name="apellido" class="form-control" value="<?=e($u['apellido'])?>"></div>
<div class="col-md-6"><label class="form-label">Telefono</label><input name="telefono" class="form-control" value="<?=e($u['telefono'])?>"></div>
<div class="col-md-6"><label class="form-label">Nueva contraseña</label><input name="password" type="password" class="form-control"></div>
<div class="col-12"><button class="btn btn-primary">Guardar</button> <?php if($u['rol']==='PACIENTE'): ?><a href="eliminar_cuenta.php" class="btn btn-danger">Eliminar cuenta</a><?php endif; ?></div></form></div>
<?php footer_html(); ?>
