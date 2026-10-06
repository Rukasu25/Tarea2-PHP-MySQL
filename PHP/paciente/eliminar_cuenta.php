<?php
require_once __DIR__ . '/../includes/common.php'; require_role('PACIENTE'); $u=user();
if($_SERVER['REQUEST_METHOD']==='POST'){
    $id=$u['id_usuario'];
    $pdo->prepare("DELETE FROM usuario WHERE id_usuario=?")->execute([$id]);
    session_destroy(); header('Location: ' . BASE_URL . '/login.php'); exit;
}
header_html('Eliminar cuenta');
?>
<div class="card p-4"><h2>Eliminar cuenta</h2>
<div class="alert alert-warning">Esta acción elimina al paciente y, por cascada, sus citas y atenciones relacionadas.</div>
<form method="post"><button class="btn btn-danger" onclick="return confirm('¿Eliminar definitivamente la cuenta?')">Eliminar mi cuenta</button>
<a href="perfil.php" class="btn btn-secondary">Cancelar</a></form></div>
<?php footer_html(); ?>
