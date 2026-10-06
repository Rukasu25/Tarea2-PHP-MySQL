<?php
require_once __DIR__ . '/config/db.php';

if (estaLogueado()) {
    redirigirSegunRol();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Debe completar todos los campos.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            $_SESSION['user'] = [
                'email' => $usuario['email'],
                'rut' => $usuario['rut'],
                'nombre' => $usuario['nombre'],
                'apellido' => $usuario['apellido'],
                'rol' => $usuario['rol'],
            ];

            // Redirigir según rol
            switch ($usuario['rol']) {
                case 'PACIENTE': header('Location: /Saludusm/paciente/dashboard.php'); exit;
                case 'MEDICO':   header('Location: /Saludusm/medico/dashboard.php');   exit;
                case 'ADMIN':    header('Location: /Saludusm/admin/dashboard.php');    exit;
            }
        } else {
            $error = 'Email o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso - SaludUSM</title>
    <!-- Frontend Bonus: Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container text-center" style="max-width: 400px;">
        <h2 class="mb-4 text-primary">SaludUSM</h2>
        
        <?php if ($error != ""): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="card p-4 shadow-sm">
            <div class="mb-3 text-start">
                <label class="form-label fw-bold">RUT (Ej: 12345678-9)</label>
                <input type="text" name="rut" class="form-control" placeholder="12345678-9" required>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Ingresar</button>
            
            <!-- Registro restringido solo a pacientes -->
            <a href="registro.php" class="text-decoration-none d-block">¿Eres paciente? Regístrate aquí</a>
        </form>
    </div>
</body>
</html>
