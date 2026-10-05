<?php
session_start();
require_once __DIR__ . '/../includes/common.php';
//NOTA IMPORTANTE!!: se debe cambiar la ruta una vez se cambien los archivos de lugar
$error = '';
$exito = '';


$previsiones = db()->query("SELECT * FROM prevision ORDER BY nombre")->fetchAll();




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut = trim($_POST['rut']);
    $nombre = trim($_POST['nombre']);
    $sexo = $_POST['sexo'];
    $fecha_nacido = $_POST['fecha_nacimiento'];
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $id_prevision = (int)($_POST['id_prevision'] ?? 0);

    //--aqui se valida el formato del RUT

    if (!preg_match("/^[0-9]{7,8}-[0-9Kk]{1}$/", $rut)){
        $error = "Formato de RUT inválido. Por favor use el formato XXXXXXXX-X sin puntos.";
    }

    //--requisito de contraseña (6 caracteres minimo)
    elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    }
    else{
        //--verificar que RUT e EMAIL sean unicos
        $stmt_check = db()->prepare("SELECT COUNT(*) FROM usuario WHERE rut = ? OR email = ?");
        $stmt_check->execute([$rut, $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'El RUT o el email ya están registrados.';
        } else {
            try {
                db()->beginTransaction();

                // 1. Insertar USUARIO
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = db()->prepare("
                    INSERT INTO usuario (email, rut, nombre, apellido, telefono, 
                                         fecha_nacimiento, sexo, password_hash, rol)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'PACIENTE')
                ");
                $stmt->execute([
                    $email, $rut, $nombre, $apellido, $telefono,
                    $fecha_nac, $sexo, $hash
                ]);

                // 2. Insertar PACIENTE
                $stmt = db()->prepare("
                    INSERT INTO paciente (email, id_prevision) VALUES (?, ?)
                ");
                $stmt->execute([$email, $id_prevision]);

                db()->commit();

                $exito = '¡Registro exitoso! Ahora puede iniciar sesión.';

            } catch (Throwable $e) {
                db()->rollBack();
                if ($e->getCode() == 23000) {
                    $error = 'El RUT o el email ya están registrados.';
                } else {
                    $error = 'Error al registrar: ' . $e->getMessage();
                }
            }
        }
    }
}


//--este es el HTML del registro

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Pacientes - SALUDUSM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-5">
    <div class="container" style="max-width: 650px;">
        <div class="card shadow-sm p-4 border-0">
            <h2 class="mb-4 text-center text-primary fw-bold">Registro de Pacientes</h2>
            <?php if ($error != ""): ?>
                <div class="alert alert-danger shadow-sm"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($exito != ""): ?>
                <div class="alert alaert-sucess shadow-sm">
                    <?php echo $exito; ?> <a href="login.php" class="alert-link">Ir al Login</a>
                </div>
            <?php endif; ?>
            <form method="POST" action="registro.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">RUT (EJ: 12345678-9)</label>
                        <input type="text" name="rut" class="form-control" placeholder="Sin puntos y con guion" required>
                    </div>
                    <div class ="col-md-6 md-3">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Sexo</label>
                        <select name="sexo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Teléfono de Contacto</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Contraseña (Mín. 6 caracteres)</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-3 py-2 fw-bold">Crear Cuenta</button>
                <div class="text-center">
                    <a href="login.php" class="text-decoration-none">¿Ya tienes cuenta? Inicia sesión aquí</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>