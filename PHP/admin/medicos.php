<?php
require_once __DIR__ . '/../includes/common.php';
require_role('ADMIN');

$error = '';
$accion = $_POST['accion'] ?? '';
$id_editar = (int)($_GET['editar'] ?? 0);

$especialidades = $pdo->query("SELECT * FROM especialidad ORDER BY nombre")->fetchAll();
$centros = $pdo->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'crear') {
    $email = trim($_POST['email'] ?? '');
    $rut = trim($_POST['rut'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $pass = $_POST['password'] ?? '';
    $esps = $_POST['especialidades'] ?? [];
    $cents = $_POST['centros'] ?? [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Email inválido.';
    elseif (!preg_match('/^\d{7,8}-[\dkK]$/', $rut)) $error = 'RUT inválido.';
    elseif (empty($nombre) || empty($apellido)) $error = 'Nombre y apellido obligatorios.';
    elseif (strlen($pass) < 6) $error = 'Contraseña muy corta.';
    elseif (count($esps) < 1 || count($esps) > 3) $error = 'Debe asignar 1 a 3 especialidades.';
    elseif (count($cents) < 1) $error = 'Debe asignar al menos 1 centro.';
    else {
        try {
            $pdo->beginTransaction();
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuario (email, rut, nombre, apellido, telefono, password_hash, rol) VALUES (?, ?, ?, ?, ?, ?, 'MEDICO')");
            $stmt->execute([$email, $rut, $nombre, $apellido, $telefono, $hash]);

            $stmt = $pdo->prepare("INSERT INTO medico (email) VALUES (?)");
            $stmt->execute([$email]);
            $id_medico = (int)$pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO medico_especialidad (id_medico, id_especialidad) VALUES (?, ?)");
            foreach ($esps as $e) $stmt->execute([$id_medico, (int)$e]);

            $stmt = $pdo->prepare("INSERT INTO medico_centro (id_medico, id_centro) VALUES (?, ?)");
            foreach ($cents as $c) $stmt->execute([$id_medico, (int)$c]);

            $pdo->commit();
            flash('Médico creado.');
            redirect('medicos.php');
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = $e->getCode() == 23000 ? 'Email o RUT ya registrados.' : $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'eliminar') {
    $id = (int)$_POST['id_medico'];
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM cita c
        JOIN estado_cita ec ON ec.id_estado = c.id_estado
        WHERE c.id_medico = ? AND c.fecha_hora > NOW() AND ec.nombre IN ('Reservada','Confirmada')
    ");
    $stmt->execute([$id]);
    if ((int)$stmt->fetchColumn() > 0) {
        flash('No se puede eliminar: tiene citas futuras.');
    } else {
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE email = (SELECT email FROM medico WHERE id_medico = ?)");
        $stmt->execute([$id]);
        flash('Médico eliminado.');
    }
    redirect('medicos.php');
}

$medico_editar = null;
$esps_actuales = [];
$cents_actuales = [];

if ($id_editar > 0) {
    $stmt = $pdo->prepare("
        SELECT m.id_medico, u.email, u.rut, u.nombre, u.apellido, u.telefono
        FROM medico m JOIN usuario u ON u.email = m.email WHERE m.id_medico = ?
    ");
    $stmt->execute([$id_editar]);
    $medico_editar = $stmt->fetch();

    if ($medico_editar) {
        $stmt = $pdo->prepare("SELECT id_especialidad FROM medico_especialidad WHERE id_medico = ?");
        $stmt->execute([$id_editar]);
        $esps_actuales = array_column($stmt->fetchAll(), 'id_especialidad');

        $stmt = $pdo->prepare("SELECT id_centro FROM medico_centro WHERE id_medico = ?");
        $stmt->execute([$id_editar]);
        $cents_actuales = array_column($stmt->fetchAll(), 'id_centro');
    }
}

$medicos = $pdo->query("
    SELECT m.id_medico, u.email, u.nombre, u.apellido,
           GROUP_CONCAT(DISTINCT e.nombre SEPARATOR ', ') AS especialidades,
           COUNT(DISTINCT c.id_cita) AS citas
    FROM medico m
    JOIN usuario u ON u.email = m.email
    LEFT JOIN medico_especialidad me ON me.id_medico = m.id_medico
    LEFT JOIN especialidad e ON e.id_especialidad = me.id_especialidad
    LEFT JOIN cita c ON c.id_medico = m.id_medico
    GROUP BY m.id_medico, u.email, u.nombre, u.apellido
    ORDER BY u.apellido, u.nombre
")->fetchAll();

header_html('Gestión de Médicos');
?>

<h2 class="mb-4">Gestión de Médicos</h2>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white"><h5 class="mb-0"><?= $medico_editar ? 'Editar' : 'Nuevo' ?> médico</h5></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="accion" value="<?= $medico_editar ? 'actualizar' : 'crear' ?>">
                    <?php if ($medico_editar): ?><input type="hidden" name="id_medico" value="<?= $medico_editar['id_medico'] ?>"><?php endif; ?>

                    <div class="mb-2"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required value="<?= e($medico_editar['email'] ?? '') ?>" <?= $medico_editar ? 'disabled' : '' ?>></div>
                    <div class="mb-2"><label class="form-label">RUT *</label><input name="rut" class="form-control" required value="<?= e($medico_editar['rut'] ?? '') ?>" <?= $medico_editar ? 'disabled' : '' ?>></div>
                    <div class="mb-2"><label class="form-label">Nombre *</label><input name="nombre" class="form-control" required value="<?= e($medico_editar['nombre'] ?? '') ?>"></div>
                    <div class="mb-2"><label class="form-label">Apellido *</label><input name="apellido" class="form-control" required value="<?= e($medico_editar['apellido'] ?? '') ?>"></div>
                    <div class="mb-2"><label class="form-label">Teléfono</label><input name="telefono" class="form-control" value="<?= e($medico_editar['telefono'] ?? '') ?>"></div>
                    <div class="mb-2"><label class="form-label">Contraseña <?= $medico_editar ? '(vacío = no cambiar)' : '*' ?></label><input type="password" name="password" class="form-control" <?= $medico_editar ? '' : 'required' ?>></div>

                    <div class="mb-2">
                        <label class="form-label">Especialidades * (1-3)</label>
                        <?php foreach ($especialidades as $esp): ?>
                            <div class="form-check">
                                <input type="checkbox" name="especialidades[]" value="<?= $esp['id_especialidad'] ?>" class="form-check-input" <?= in_array($esp['id_especialidad'], $esps_actuales) ? 'checked' : '' ?>>
                                <label class="form-check-label"><?= e($esp['nombre']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Centros * (al menos 1)</label>
                        <?php foreach ($centros as $c): ?>
                            <div class="form-check">
                                <input type="checkbox" name="centros[]" value="<?= $c['id_centro'] ?>" class="form-check-input" <?= in_array($c['id_centro'], $cents_actuales) ? 'checked' : '' ?>>
                                <label class="form-check-label"><?= e($c['nombre']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="btn btn-success w-100">Guardar</button>
                    <?php if ($medico_editar): ?><a href="medicos.php" class="btn btn-secondary w-100 mt-2">Cancelar</a><?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-dark text-white"><h5 class="mb-0">Médicos (<?= count($medicos) ?>)</h5></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Médico</th><th>Especialidades</th><th>Citas</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($medicos as $m): ?>
                            <tr>
                                <td><strong>Dr/a. <?= e($m['nombre'].' '.$m['apellido']) ?></strong><br><small><?= e($m['email']) ?></small></td>
                                <td><small><?= e($m['especialidades'] ?: 'Sin especialidades') ?></small></td>
                                <td><?= $m['citas'] ?></td>
                                <td>
                                    <a href="medicos.php?editar=<?= $m['id_medico'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id_medico" value="<?= $m['id_medico'] ?>">
                                        <button class="btn btn-sm btn-outline-danger">X</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>