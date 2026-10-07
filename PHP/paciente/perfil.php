<?php
require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();
$error = '';

$stmt = $pdo->prepare("
    SELECT u.email, u.rut, u.nombre, u.apellido, u.telefono, 
           u.fecha_nacimiento, u.sexo, p.nombre AS prevision
    FROM usuario u
    JOIN paciente pa ON pa.email = u.email
    JOIN prevision p ON p.id_prevision = pa.id_prevision
    WHERE u.email = ?
");
$stmt->execute([$u['email']]);
$paciente = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (empty($nombre) || empty($apellido)) {
        $error = 'Nombre y apellido son obligatorios.';
    } elseif ($pass !== '' && strlen($pass) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($pass !== '' && $pass !== $pass2) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        if ($pass !== '') {
            $stmt = $pdo->prepare("UPDATE usuario SET nombre = ?, apellido = ?, telefono = ?, password_hash = ? WHERE email = ?");
            $stmt->execute([$nombre, $apellido, $telefono, password_hash($pass, PASSWORD_DEFAULT), $u['email']]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuario SET nombre = ?, apellido = ?, telefono = ? WHERE email = ?");
            $stmt->execute([$nombre, $apellido, $telefono, $u['email']]);
        }
        $_SESSION['user']['nombre'] = $nombre;
        $_SESSION['user']['apellido'] = $apellido;
        flash('Datos actualizados.');
        redirect('perfil.php');
    }
}

header_html('Mi Perfil');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white"><h4 class="mb-0">Mi Perfil</h4></div>
            <div class="card-body">
                <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-6"><label class="text-muted">Email</label><input class="form-control" value="<?= e($paciente['email']) ?>" disabled></div>
                    <div class="col-md-6"><label class="text-muted">RUT</label><input class="form-control" value="<?= e($paciente['rut']) ?>" disabled></div>
                    <div class="col-md-6 mt-3"><label class="text-muted">Fecha nacimiento</label><input class="form-control" value="<?= e($paciente['fecha_nacimiento']) ?>" disabled></div>
                    <div class="col-md-6 mt-3"><label class="text-muted">Previsión</label><input class="form-control" value="<?= e($paciente['prevision']) ?>" disabled></div>
                </div>

                <hr>

                <form method="POST" class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nombre *</label><input name="nombre" class="form-control" required value="<?= e($paciente['nombre']) ?>"></div>
                    <div class="col-md-6"><label class="form-label">Apellido *</label><input name="apellido" class="form-control" required value="<?= e($paciente['apellido']) ?>"></div>
                    <div class="col-md-6"><label class="form-label">Teléfono</label><input name="telefono" class="form-control" value="<?= e($paciente['telefono']) ?>"></div>
                    <div class="col-md-6"><label class="form-label">Nueva contraseña</label><input name="password" type="password" class="form-control" placeholder="Vacío = no cambiar"></div>
                    <div class="col-md-6"><label class="form-label">Repetir contraseña</label><input name="password2" type="password" class="form-control"></div>
                    <div class="col-12 mt-4">
                        <button class="btn btn-primary">Guardar</button>
                        <a href="eliminar_cuenta.php" class="btn btn-danger float-end" onclick="return confirm('¿Eliminar cuenta?')">Eliminar cuenta</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>