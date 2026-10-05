<?php
require_once __DIR__ . '/includes/common.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $rut=trim($_POST['rut']??''); $nombre=trim($_POST['nombre']??''); $apellido=trim($_POST['apellido']??'');
    $email=trim($_POST['email']??''); $telefono=trim($_POST['telefono']??''); $fecha=$_POST['fecha_nacimiento']??'';
    $sexo=$_POST['sexo']??null; $prev=(int)($_POST['id_prevision']??0); $pass=$_POST['password']??'';
    if(!preg_match('/^\d{7,8}-[\dkK]$/',$rut)) $error='RUT debe tener formato XXXXXXXX-X.';
    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)) $error='Email invalido.';
    elseif(strlen($pass)<10 || !preg_match('/[A-Z]/',$pass) || !preg_match('/[a-z]/',$pass) || !preg_match('/\d/',$pass)) $error='La contraseña debe tener 10 caracteres, mayuscula, minuscula y numero.';
    else {
        try{
            db()->beginTransaction();
            $s=db()->prepare("INSERT INTO usuario(rut,nombre,apellido,email,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES(?,?,?,?,?,?,?,?, 'PACIENTE')");
            $s->execute([$rut,$nombre,$apellido,$email,$telefono,$fecha,$sexo,password_hash($pass,PASSWORD_DEFAULT)]);
            $id=(int)db()->lastInsertId();
            db()->prepare("INSERT INTO paciente(id_paciente,id_prevision) VALUES(?,?)")->execute([$id,$prev]);
            db()->commit(); flash('Registro exitoso. Ahora puede ingresar.'); redirect('index.php');
        }catch(Throwable $e){ if(db()->inTransaction()) db()->rollBack(); $error='No fue posible registrar: '.$e->getMessage(); }
    }
}
$prev=db()->query("SELECT * FROM prevision ORDER BY nombre")->fetchAll();
header_html('Registro');
?>
<div class="card p-4"><h2>Registro de paciente</h2>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post" class="row g-3">
<div class="col-md-4"><label class="form-label">RUT</label><input name="rut" class="form-control" placeholder="12345678-9" required></div>
<div class="col-md-4"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Telefono</label><input name="telefono" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Fecha nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Sexo</label><select name="sexo" class="form-select"><option value="F">F</option><option value="M">M</option><option value="X">X</option></select></div>
<div class="col-md-4"><label class="form-label">Prevision</label><select name="id_prevision" class="form-select" required><?php foreach($prev as $p):?><option value="<?=$p['id_prevision']?>"><?=e($p['nombre'])?></option><?php endforeach;?></select></div>
<div class="col-md-6"><label class="form-label">Contraseña</label><input type="password" name="password" class="form-control" required></div>
<div class="col-12"><button class="btn btn-primary">Crear cuenta</button> <a class="btn btn-secondary" href="login.php">Volver</a></div>
</form></div>
<?php footer_html(); ?>
