<?php
require_once __DIR__ . '/../includes/common.php';
require_role('MEDICO');

$u = user();
$error = '';

$stmt = $pdo->prepare("SELECT id_medico FROM medico WHERE email = ?");
$stmt->execute([$u['email']]);
$id_medico = (int)$stmt->fetchColumn();

$id_cita = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT c.id_cita, c.fecha_hora, c.id_paciente, c.id_especialidad, c.id_centro,
           ec.nombre AS estado,
           CONCAT(up.nombre,' ',up.apellido) AS paciente,
           up.rut AS rut_paciente,
           p.nombre AS prevision,
           e.nombre AS especialidad,
           cm.nombre AS centro
    FROM cita c
    JOIN paciente pa ON pa.id_paciente = c.id_paciente
    JOIN usuario up ON up.email = pa.email
    JOIN prevision p ON p.id_prevision = pa.id_prevision
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN especialidad e ON e.id_especialidad = c.id_especialidad
    JOIN centro_medico cm ON cm.id_centro = c.id_centro
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    WHERE c.id_cita = ? AND c.id_medico = ?
");
$stmt->execute([$id_cita, $id_medico]);
$cita = $stmt->fetch();

if (!$cita || $cita['estado'] !== 'Atendida') {
    flash('Cita no válida o no está en estado Atendida.');
    redirect('agenda.php');
}

$stmt = $pdo->prepare("SELECT id_atencion FROM atencion WHERE id_cita = ?");
$stmt->execute([$id_cita]);
$id_atencion = (int)$stmt->fetchColumn();

$atencion = ['motivo' => '', 'observaciones' => ''];
$diagnosticos = [];
$recetas = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'guardar') {
        $motivo = trim($_POST['motivo'] ?? '');
        $obs = trim($_POST['observaciones'] ?? '');
        if (empty($motivo)) {
            $error = 'El motivo es obligatorio.';
        } else {
            if ($id_atencion > 0) {
                $stmt = $pdo->prepare("UPDATE atencion SET motivo = ?, observaciones = ? WHERE id_atencion = ?");
                $stmt->execute([$motivo, $obs, $id_atencion]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO atencion (id_cita, motivo, observaciones) VALUES (?, ?, ?)");
                $stmt->execute([$id_cita, $motivo, $obs]);
                $id_atencion = (int)$pdo->lastInsertId();
            }
            flash('Atención guardada.');
            redirect('atencion.php?id=' . $id_cita);
        }
    } elseif ($accion === 'add_diagnostico' && $id_atencion > 0) {
        $codigo = trim($_POST['codigo_cie10'] ?? '');
        $desc = trim($_POST['descripcion'] ?? '');
        if ($codigo && $desc) {
            $stmt = $pdo->prepare("SELECT id_diagnostico FROM diagnostico WHERE codigo_cie10 = ?");
            $stmt->execute([$codigo]);
            $id_diag = $stmt->fetchColumn();

            if (!$id_diag) {
                $stmt = $pdo->prepare("INSERT INTO diagnostico (codigo_cie10, descripcion) VALUES (?, ?)");
                $stmt->execute([$codigo, $desc]);
                $id_diag = $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare("INSERT IGNORE INTO diagnostico_atencion (id_atencion, id_diagnostico) VALUES (?, ?)");
            $stmt->execute([$id_atencion, $id_diag]);
        }
        redirect('atencion.php?id=' . $id_cita);
    } elseif ($accion === 'del_diagnostico' && $id_atencion > 0) {
        $id_diag = (int)$_POST['id_diagnostico'];
        $stmt = $pdo->prepare("DELETE FROM diagnostico_atencion WHERE id_atencion = ? AND id_diagnostico = ?");
        $stmt->execute([$id_atencion, $id_diag]);
        redirect('atencion.php?id=' . $id_cita);
    } elseif ($accion === 'add_receta' && $id_atencion > 0) {
        $med = trim($_POST['medicamento'] ?? '');
        $dosis = trim($_POST['dosis'] ?? '');
        $dias = (int)$_POST['dias_tratamiento'];
        if ($med && $dosis && $dias > 0) {
            $stmt = $pdo->prepare("SELECT id_medicamento FROM medicamento WHERE nombre = ?");
            $stmt->execute([$med]);
            $id_med = $stmt->fetchColumn();

            if (!$id_med) {
                $stmt = $pdo->prepare("INSERT INTO medicamento (nombre) VALUES (?)");
                $stmt->execute([$med]);
                $id_med = $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare("INSERT INTO receta_linea (id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_atencion, $id_med, $dosis, $dias]);
        }
        redirect('atencion.php?id=' . $id_cita);
    } elseif ($accion === 'del_receta' && $id_atencion > 0) {
        $id_rec = (int)$_POST['id_receta_linea'];
        $stmt = $pdo->prepare("DELETE FROM receta_linea WHERE id_receta_linea = ? AND id_atencion = ?");
        $stmt->execute([$id_rec, $id_atencion]);
        redirect('atencion.php?id=' . $id_cita);
    }
}

if ($id_atencion > 0) {
    $stmt = $pdo->prepare("SELECT * FROM atencion WHERE id_atencion = ?");
    $stmt->execute([$id_atencion]);
    $atencion = $stmt->fetch();

    $stmt = $pdo->prepare("
        SELECT d.id_diagnostico, d.codigo_cie10, d.descripcion
        FROM diagnostico_atencion da
        JOIN diagnostico d ON d.id_diagnostico = da.id_diagnostico
        WHERE da.id_atencion = ?
    ");
    $stmt->execute([$id_atencion]);
    $diagnosticos = $stmt->fetchAll();

    $stmt = $pdo->prepare("
        SELECT rl.id_receta_linea, m.nombre AS medicamento, rl.dosis, rl.dias_tratamiento
        FROM receta_linea rl
        JOIN medicamento m ON m.id_medicamento = rl.id_medicamento
        WHERE rl.id_atencion = ?
    ");
    $stmt->execute([$id_atencion]);
    $recetas = $stmt->fetchAll();
}

header_html('Registrar Atención');
?>

<h2 class="mb-4">Atención - <?= e($cita['paciente']) ?></h2>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <p><strong>Fecha:</strong> <?= date('d-m-Y H:i', strtotime($cita['fecha_hora'])) ?></p>
        <p><strong>Especialidad:</strong> <?= e($cita['especialidad']) ?> - <?= e($cita['centro']) ?></p>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white"><h5 class="mb-0">Datos de la atención</h5></div>
    <div class="card-body">
        <form method="POST" class="row g-3">
            <input type="hidden" name="accion" value="guardar">
            <div class="col-md-12"><label class="form-label">Motivo *</label><textarea name="motivo" class="form-control" required><?= e($atencion['motivo']) ?></textarea></div>
            <div class="col-md-12"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control"><?= e($atencion['observaciones']) ?></textarea></div>
            <div class="col-12"><button class="btn btn-primary">Guardar</button></div>
        </form>
    </div>
</div>

<?php if ($id_atencion > 0): ?>
<div class="card shadow mb-4">
    <div class="card-header bg-dark text-white"><h5 class="mb-0">Diagnósticos</h5></div>
    <div class="card-body">
        <?php if ($diagnosticos): ?>
            <table class="table table-sm">
                <?php foreach ($diagnosticos as $d): ?>
                    <tr>
                        <td><span class="badge bg-dark"><?= e($d['codigo_cie10']) ?></span></td>
                        <td><?= e($d['descripcion']) ?></td>
                        <td>
                            <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                                <input type="hidden" name="accion" value="del_diagnostico">
                                <input type="hidden" name="id_diagnostico" value="<?= $d['id_diagnostico'] ?>">
                                <button class="btn btn-sm btn-danger">X</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <hr>
        <form method="POST" class="row g-2">
            <input type="hidden" name="accion" value="add_diagnostico">
            <div class="col-md-2"><input name="codigo_cie10" class="form-control" placeholder="I10" required></div>
            <div class="col-md-8"><input name="descripcion" class="form-control" placeholder="Descripción" required></div>
            <div class="col-md-2"><button class="btn btn-success w-100">Agregar</button></div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-success text-white"><h5 class="mb-0">Recetas</h5></div>
    <div class="card-body">
        <?php if ($recetas): ?>
            <table class="table table-sm">
                <?php foreach ($recetas as $r): ?>
                    <tr>
                        <td><?= e($r['medicamento']) ?></td>
                        <td><?= e($r['dosis']) ?></td>
                        <td><?= e($r['dias_tratamiento']) ?> días</td>
                        <td>
                            <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                                <input type="hidden" name="accion" value="del_receta">
                                <input type="hidden" name="id_receta_linea" value="<?= $r['id_receta_linea'] ?>">
                                <button class="btn btn-sm btn-danger">X</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <hr>
        <form method="POST" class="row g-2">
            <input type="hidden" name="accion" value="add_receta">
            <div class="col-md-4"><input name="medicamento" class="form-control" placeholder="Medicamento" required></div>
            <div class="col-md-4"><input name="dosis" class="form-control" placeholder="Dosis" required></div>
            <div class="col-md-2"><input type="number" name="dias_tratamiento" class="form-control" placeholder="Días" min="1" required></div>
            <div class="col-md-2"><button class="btn btn-success w-100">Agregar</button></div>
        </form>
    </div>
</div>
<?php endif; ?>

<a href="agenda.php" class="btn btn-secondary">Volver a la agenda</a>

<?php footer_html(); ?>