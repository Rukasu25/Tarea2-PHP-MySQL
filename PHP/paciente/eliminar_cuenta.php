<?php
require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT password_hash FROM usuario WHERE email = ?");
    $stmt->execute([$u['email']]);
    $hash = $stmt->fetchColumn();

    if (!password_verify($pass, $hash)) {
        $error = 'Contraseña incorrecta.';
    } else {
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE email = ?");
        $stmt->execute([$u['email']]);
        session_destroy();
        header('Location: ' . url('login.php'));
        exit;
    }
}

header_html('Eliminar Cuenta');
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-danger shadow">
            <div class="card-header bg-danger text-white"><h4 class="mb-0">Eliminar cuenta</h4></div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong>¡Atención!</strong> Se eliminarán tu cuenta, citas, atenciones, diagnósticos y recetas. No se puede deshacer.
                </div>
                <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3"><label class="form-label">Confirma tu contraseña</label><input type="password" name="password" class="form-control" required></div>
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('¿Seguro?')">Eliminar cuenta</button>
                    <a href="perfil.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>