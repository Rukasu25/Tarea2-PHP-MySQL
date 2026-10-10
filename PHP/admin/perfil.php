<?php
require_once __DIR__ . '/../includes/common.php';
require_role('ADMIN');

$u = user();
$error = '';

// Obtener datos del admin
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
$stmt->execute([$u['email']]);
$admin = $stmt->fetch();

if (!$admin) {
    die('Error: No se encontró el usuario.');
}

// Procesar actualización
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
        try {
            if ($pass !== '') {
                $stmt = $pdo->prepare("
                    UPDATE usuario 
                    SET nombre = ?, apellido = ?, telefono = ?, password_hash = ?
                    WHERE email = ?
                ");
                $stmt->execute([$nombre, $apellido, $telefono, password_hash($pass, PASSWORD_DEFAULT), $u['email']]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE usuario 
                    SET nombre = ?, apellido = ?, telefono = ?
                    WHERE email = ?
                ");
                $stmt->execute([$nombre, $apellido, $telefono, $u['email']]);
            }

            // Actualizar sesión
            $_SESSION['user']['nombre'] = $nombre;
            $_SESSION['user']['apellido'] = $apellido;

            flash('Datos actualizados correctamente.');
            redirect('admin/perfil.php');

        } catch (Throwable $e) {
            $error = 'Error al actualizar: ' . $e->getMessage();
        }
    }
}

header_html('Mi Perfil - Administrador');
?>

<div class="row justify-content-center">
    <div class="col-md-8">

        <!-- Encabezado -->
        <div class="card shadow mb-4 border-start border-4 border-primary">
            <div class="card-body">
                <h3 class="mb-1">
                    <i class="bi bi-person-badge"></i>
                    <?= e($admin['nombre'] . ' ' . $admin['apellido']) ?>
                </h3>
                <p class="text-muted mb-0">
                    <span class="badge bg-danger">Administrador</span>
                    <span class="ms-2"><?= e($admin['email']) ?></span>
                </p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <!-- Datos no editables -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-lock"></i> Datos no editables</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <input type="text" class="form-control" value="<?= e($admin['email']) ?>" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">RUT</label>
                        <input type="text" class="form-control" value="<?= e($admin['rut']) ?>" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Rol</label>
                        <input type="text" class="form-control" value="<?= e($admin['rol']) ?>" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Fecha de registro</label>
                        <input type="text" class="form-control" 
                               value="<?= e($admin['fecha_registro'] ?? 'No disponible') ?>" disabled>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos editables -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Editar mis datos</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required
                               value="<?= e($admin['nombre']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" required
                               value="<?= e($admin['apellido']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                               value="<?= e($admin['telefono'] ?? '') ?>">
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="text-muted">Cambiar contraseña (opcional)</h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" minlength="6"
                               placeholder="Dejar vacío para no cambiar">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Repetir nueva contraseña</label>
                        <input type="password" name="password2" class="form-control" minlength="6"
                               placeholder="Repetir contraseña">
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar cambios
                        </button>
                        <a href="admin.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al panel
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php footer_html(); ?>