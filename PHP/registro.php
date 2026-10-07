<?php
require_once __DIR__ . '/includes/common.php';

if (user()) {
    redirect('index.php');
}

$error = '';
$exito = '';
$previsiones = $pdo->query("SELECT * FROM prevision ORDER BY nombre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $rut = trim($_POST['rut'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nac = $_POST['fecha_nacimiento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $id_prevision = (int)($_POST['id_prevision'] ?? 0);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido.';
    } elseif (!preg_match('/^\d{7,8}-[\dkK]$/', $rut)) {
        $error = 'Formato de RUT inválido.';
    } elseif (empty($nombre) || empty($apellido)) {
        $error = 'Nombre y apellido son obligatorios.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $password2) {
        $error = 'Las contraseñas no coinciden.';
    } elseif ($id_prevision <= 0) {
        $error = 'Debe seleccionar una previsión.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE email = ? OR rut = ?");
        $stmt->execute([$email, $rut]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'El RUT o el email ya están registrados.';
        } else {
            try {
                $pdo->beginTransaction();

                // ✅ Aquí se genera el hash automáticamente
                $hash = password_hash($password, PASSWORD_DEFAULT);

                // ✅ Insertar en USUARIO (columnas correctas)
                $stmt = $pdo->prepare("
                    INSERT INTO usuario (email, rut, nombre, apellido, telefono, 
                                         fecha_nacimiento, sexo, password_hash, rol)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'PACIENTE')
                ");
                $stmt->execute([$email, $rut, $nombre, $apellido, $telefono,
                                $fecha_nac, $sexo, $hash]);

                // ✅ Insertar en PACIENTE
                $stmt = $pdo->prepare("INSERT INTO paciente (email, id_prevision) VALUES (?, ?)");
                $stmt->execute([$email, $id_prevision]);

                $pdo->commit();
                $exito = '¡Registro exitoso! Ya puede iniciar sesión.';
            } catch (PDOException $e) {
                $pdo->rollBack();
                if ($e->getCode() == 23000) {
                    $error = 'El RUT o el email ya están registrados.';
                } else {
                    $error = 'Error de base de datos: ' . $e->getMessage();
                }
            }
        }
    }
}

header_html('Registro de Pacientes');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0"><i class="bi bi-person-plus"></i> Registro de Pacientes</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                <?php if ($exito): ?>
                    <div class="alert alert-success">
                        <?= e($exito) ?> <a href="<?= url('login.php') ?>" class="alert-link">Ir al Login</a>
                    </div>
                <?php endif; ?>

                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">RUT *</label>
                        <input type="text" name="rut" class="form-control" placeholder="12345678-9" required
                               value="<?= e($_POST['rut'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control" required
                               value="<?= e($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required
                               value="<?= e($_POST['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" required
                               value="<?= e($_POST['apellido'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sexo *</label>
                        <select name="sexo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="F" <?= ($_POST['sexo'] ?? '') === 'F' ? 'selected' : '' ?>>Femenino</option>
                            <option value="M" <?= ($_POST['sexo'] ?? '') === 'M' ? 'selected' : '' ?>>Masculino</option>
                            <option value="X" <?= ($_POST['sexo'] ?? '') === 'X' ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha de nacimiento *</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" required
                               value="<?= e($_POST['fecha_nacimiento'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                               value="<?= e($_POST['telefono'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Previsión *</label>
                        <select name="id_prevision" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            <?php foreach ($previsiones as $p): ?>
                                <option value="<?= $p['id_prevision'] ?>"
                                    <?= ($_POST['id_prevision'] ?? '') == $p['id_prevision'] ? 'selected' : '' ?>>
                                    <?= e($p['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Contraseña *</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Repetir contraseña *</label>
                        <input type="password" name="password2" class="form-control" required minlength="6">
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            <i class="bi bi-person-plus"></i> Crear Cuenta
                        </button>
                        <div class="text-center mt-3">
                            <a href="<?= url('login.php') ?>">¿Ya tienes cuenta? Inicia sesión aquí</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>