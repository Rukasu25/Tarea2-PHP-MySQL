<?php
require_once __DIR__ . '/includes/common.php';

if (user()) {
    switch (user()['rol']) {
        case 'PACIENTE': redirect('paciente/dashboard.php');
        case 'MEDICO':   redirect('medico/dashboard.php');
        case 'ADMIN':    redirect('admin/admin.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Debe completar todos los campos.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            $_SESSION['user'] = [
                'email'    => $usuario['email'],
                'rut'      => $usuario['rut'],
                'nombre'   => $usuario['nombre'],
                'apellido' => $usuario['apellido'],
                'rol'      => $usuario['rol'],
            ];

            switch ($usuario['rol']) {
                case 'PACIENTE': redirect('paciente/dashboard.php');
                case 'MEDICO':   redirect('medico/dashboard.php');
                case 'ADMIN':    redirect('admin/admin.php');
                default:         redirect('index.php');
            }
        } else {
            $error = 'Email o contraseña incorrectos.';
        }
    }
}

header_html('Iniciar Sesión');
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus
                               value="<?= e($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Ingresar
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>