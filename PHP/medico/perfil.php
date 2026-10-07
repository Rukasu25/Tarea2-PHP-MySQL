<?php
require_once __DIR__ . '/../includes/common.php';
require_role('MEDICO');

$u = user();
$error = '';

$stmt = $pdo->prepare("
    SELECT u.email, u.rut, u.nombre, u.apellido, u.telefono, u.fecha_nacimiento, u.sexo,
           m.id_medico,
           GROUP_CONCAT(DISTINCT e.nombre SEPARATOR ', ') AS especialidades,
           GROUP_CONCAT(DISTINCT cm.nombre SEPARATOR ', ') AS centros
    FROM usuario u
    JOIN medico m ON m.email = u.email
    LEFT JOIN medico_especialidad me ON me.id_medico = m.id_medico
    LEFT JOIN especialidad e ON e.id_especialidad = me.id_especialidad
    LEFT JOIN medico_centro mc ON mc.id_medico = m.id_medico
    LEFT JOIN centro_medico cm ON cm.id_centro = mc.id_centro
    WHERE u.email = ?
    GROUP BY u.email, u.rut, u.nombre, u.apellido, u.telefono, u.fecha_nacimiento, u.sexo, m.id_medico
");
$stmt->execute([$u['email']]);
$medico = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $pass = $_POST['password'] ?? '';

    if (empty($nombre) || empty($apellido)) {
        $error = 'Nombre y apellido son obligatorios.';
    } elseif ($pass !== '' && strlen($pass) < 6) {
        $error = 'Contraseña muy corta.';
    } else {
        if ($pass !== '') {
            $stmt = $pdo->prepare("UPDATE usuario SET nombre=?, apellido=?, telefono=?, password_hash=? WHERE email=?");
            $stmt->execute([$nombre, $apellido, $telefono, password_hash($pass, PASSWORD_DEFAULT), $u['email']]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuario SET nombre=?, apellido=?, telefono=? WHERE email=?");
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
                    <div class="col-md-6"><label class="text-muted">Email</label><input class="form-control" value="<?= e($medico['email']) ?>" disabled></div>
                    <div class="col-md-6"><label class="text-muted">RUT</label><input class="form-control" value="<?= e($medico['rut']) ?>" disabled></div>
                    <div class="col-md-6 mt-3"><label class="text-muted">Especialidades</label><input class="form-control" value="<?= e($medico['especialidades'] ?: 'Sin especialidades') ?>" disabled></div>
                    <div class="col-md-6 mt-3"><label class="text-muted">Centros</label><input class="form-control" value="<?= e($medico['centros'] ?: 'Sin centros') ?>" disabled></div>
                </div>

                <hr>

                <form method="POST" class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nombre *</label><input name="nombre" class="form-control" required value="<?= e($medico['nombre']) ?>"></div>
                    <div class="col-md-6"><label class="form-label">Apellido *</label><input name="apellido" class="form-control" required value="<?= e($medico['apellido']) ?>"></div>
                    <div class="col-md-6"><label class="form-label">Teléfono</label><input name="telefono" class="form-control" value="<?= e($medico['telefono']) ?>"></div>
                    <div class="col-md-6"><label class="form-label">Nueva contraseña</label><input name="password" type="password" class="form-control"></div>
                    <div class="col-12 mt-4">
                        <button class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>