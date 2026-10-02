<?php
session_start();
require 'conexion.php';
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut= trim($_POST['rut']);
    $password = $_POST['password'];

    //Nota de Rukasu: aqui quedé, sigue por mi, voy a almorzar xd
    // Validación obligatoria del formato de RUT (XXXXXXXX-X)
    if (!preg_match("/^[0-9]{7,8}-[0-9Kk]{1}$/", $rut)) {
        $error = "Formato de RUT inválido. Use el formato XXXXXXXX-X.";
    } else {
        // 1. Verificar si es Administrador
        $stmt_admin = $conn->prepare("SELECT Rut_Admin, Contrasena FROM Administrador WHERE Rut_Admin = ?");
        $stmt_admin->bind_param("s", $rut);
        $stmt_admin->execute();
        $res_admin = $stmt_admin->get_result();

        if ($row = $res_admin->fetch_assoc()) {
            if ($password === $row['Contrasena']) {
                $_SESSION['rut'] = $row['Rut_Admin'];
                $_SESSION['rol'] = 3; // 3 = Administrador
                header("Location: principal.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            // 2. Verificar si es Paciente
            $stmt_pac = $conn->prepare("SELECT Rut_Paciente, Contrasena FROM Paciente WHERE Rut_Paciente = ?");
            $stmt_pac->bind_param("s", $rut);
            $stmt_pac->execute();
            $res_pac = $stmt_pac->get_result();

            if ($row = $res_pac->fetch_assoc()) {
                // password_verify se usa porque guardaremos la contraseña encriptada en el registro
                if (password_verify($password, $row['Contrasena'])) {
                    $_SESSION['rut'] = $row['Rut_Paciente'];
                    $_SESSION['rol'] = 1; // 1 = Paciente
                    header("Location: principal.php");
                    exit();
                } else {
                    $error = "Contraseña incorrecta.";
                }
            } else {
                // 3. Verificar si es Médico
                $stmt_med = $conn->prepare("SELECT Rut_Medico, Contrasena FROM Medico WHERE Rut_Medico = ?");
                $stmt_med->bind_param("s", $rut);
                $stmt_med->execute();
                $res_med = $stmt_med->get_result();

                if ($row = $res_med->fetch_assoc()) {
                    if ($password === $row['Contrasena']) {
                        $_SESSION['rut'] = $row['Rut_Medico'];
                        $_SESSION['rol'] = 2; // 2 = Médico
                        header("Location: principal.php");
                        exit();
                    } else {
                        $error = "Contraseña incorrecta.";
                    }
                } else {
                    $error = "RUT no encontrado en el sistema.";
                }
            }
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
