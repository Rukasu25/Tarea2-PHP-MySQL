<?php
require_once __DIR__ . '/../includes/common.php';
require_login();

$u = user();
$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $pass = $_POST['password'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($apellido)) {
        $error = 'Nombre y apellido son obligatorios.';
    } else {
        try {
            if ($pass !== '') {
                // Validar contraseña
                if (strlen($pass) < 10 
                    || !preg_match('/[A-Z]/', $pass) 
                    || !preg_match('/[a-z]/', $pass) 
                    || !preg_match('/\d/', $pass)) {
                    throw new Exception('La nueva contraseña debe tener al menos 10 caracteres, una mayúscula, una minúscula y un número.');
                }

                $stmt = $pdo->prepare("
                    UPDATE usuario 
                    SET nombre = ?, apellido = ?, telefono = ?, password_hash = ?
                    WHERE email = ?
                ");
                $stmt->execute([
                    $nombre, $apellido, $telefono,
                    password_hash($pass, PASSWORD_DEFAULT),
                    $u['email']
                ]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE usuario 
                    SET nombre = ?, apellido = ?, telefono = ?
                    WHERE email = ?
                ");
                $stmt->execute([$nombre, $apellido, $telefono, $u['email']]);
            }

            // Actualizar sesión
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
            $stmt->execute([$u['email']]);
            $_SESSION['user'] = $stmt->fetch();

            flash('Datos actualizados correctamente.');
            redirect('perfil.php');

        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}

header_html('Mi Perfil');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-person-circle"></i> Mis Datos</h4>
            </div>
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if ($exito): ?>
                    <div class="alert alert-success"><?= e($exito) ?></div>
                <?php endif; ?>

                <!-- Información no editable -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email</label>
                        <input type="text" class="form-control" value="<?= e($u['email']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">RUT</label>
                        <input type="text" class="form-control" value="<?= e($u['rut']) ?>" disabled>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label text-muted">Rol</label>
                        <input type="text" class="form-control" value="<?= e($u['rol']) ?>" disabled>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label text-muted">Fecha de nacimiento</label>
                        <input type="text" class="form-control" 
                               value="<?= e($u['fecha_nacimiento'] ?? 'No registrada') ?>" disabled>
                    </div>
                </div>

                <hr>

                <!-- Formulario editable -->
                <form method="post" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <input name="nombre" class="form-control" required
                               value="<?= e($u['nombre']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido *</label>
                        <input name="apellido" class="form-control" required
                               value="<?= e($u['apellido']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input name="telefono" class="form-control"
                               value="<?= e($u['telefono'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nueva contraseña</label>
                        <input name="password" type="password" class="form-control"
                               placeholder="Dejar vacío para no cambiar">
                        <small class="text-muted">
                            Mínimo 10 caracteres, 1 mayúscula, 1 minúscula, 1 número.
                        </small>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar cambios
                        </button>

                        <?php if ($u['rol'] === 'PACIENTE'): ?>
                            <a href="eliminar_cuenta.php" class="btn btn-danger float-end"
                               onclick="return confirm('¿Estás seguro de eliminar tu cuenta? Esta acción no se puede deshacer.')">
                                <i class="bi bi-trash"></i> Eliminar cuenta
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>