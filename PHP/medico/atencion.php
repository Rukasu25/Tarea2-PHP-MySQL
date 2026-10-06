<?php
require_once __DIR__ . '/../includes/common.php'; require_role('MEDICO'); $u=user();
$id=(int)($_GET['id']??0); $error='';
$s=$pdo->prepare("SELECT * FROM vw_citas_detalle WHERE id_cita=? AND id_medico=?"); $s->execute([$id,$u['id_usuario']]); $c=$s->fetch();
if(!$c || $c['estado']!=='Atendida') exit('La cita no existe, no pertenece al medico o no esta Atendida.');
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        $pdo->beginTransaction();
        $q=$pdo->prepare("INSERT INTO atencion(id_cita,motivo,observaciones) VALUES(?,?,?)
        ON DUPLICATE KEY UPDATE motivo=VALUES(motivo), observaciones=VALUES(observaciones)");
        $q->execute([$id,trim($_POST['motivo']),trim($_POST['observaciones'])]);
        $aid=(int)$pdo->query("SELECT id_atencion FROM atencion WHERE id_cita=$id")->fetchColumn();
        $pdo->prepare("DELETE FROM diagnostico_atencion WHERE id_atencion=?")->execute([$aid]);
        foreach(($_POST['diagnosticos']??[]) as $did) $pdo->prepare("INSERT INTO diagnostico_atencion VALUES(?,?)")->execute([$aid,(int)$did]);
        if(!empty($_POST['medicamento'])){
            $pdo->prepare("INSERT INTO receta_linea(id_atencion,id_medicamento,dosis,dias_tratamiento) VALUES(?,?,?,?)")
            ->execute([$aid,(int)$_POST['medicamento'],trim($_POST['dosis']),max(1,(int)$_POST['dias'])]);
        }
        $pdo->commit(); flash('Atencion guardada.'); redirect('agenda.php');
    }catch(Throwable $e){ if($pdo->inTransaction())$pdo->rollBack(); $error=$e->getMessage(); }
}
$diag=$pdo->query("SELECT * FROM diagnostico ORDER BY codigo_cie10")->fetchAll();
$meds=$pdo->query("SELECT * FROM medicamento ORDER BY nombre")->fetchAll();
header_html('Registrar atencion');
?>
<div class="card p-4"><h2>Ficha de atencion</h2>
<p><b>Paciente:</b> <?=e($c['paciente'])?> | <b>Fecha:</b> <?=e($c['fecha_hora'])?> | <b>Centro:</b> <?=e($c['centro'])?></p>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post">
<label class="form-label">Motivo de consulta</label><textarea name="motivo" class="form-control mb-3" required></textarea>
<label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control mb-3"></textarea>
<label class="form-label">Diagnosticos</label><select name="diagnosticos[]" multiple class="form-select mb-3" size="6"><?php foreach($diag as $d):?><option value="<?=$d['id_diagnostico']?>"><?=e($d['codigo_cie10'].' - '.$d['descripcion'])?></option><?php endforeach;?></select>
<h5>Receta (una linea inicial)</h5>
<div class="row g-2 mb-3"><div class="col"><select name="medicamento" class="form-select"><option value="">Sin receta</option><?php foreach($meds as $m):?><option value="<?=$m['id_medicamento']?>"><?=e($m['nombre'])?></option><?php endforeach;?></select></div><div class="col"><input name="dosis" class="form-control" placeholder="Dosis"></div><div class="col"><input name="dias" type="number" min="1" class="form-control" placeholder="Dias"></div></div>
<button class="btn btn-primary">Guardar atencion</button>
</form></div>
<?php footer_html(); ?>
