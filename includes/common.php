<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../config/db.php';

const BASE_URL = '/saludusm';

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): never { header("Location: $url"); exit; }
function flash(?string $msg=null): ?string {
    if ($msg !== null) $_SESSION['flash']=$msg;
    $x=$_SESSION['flash']??null; unset($_SESSION['flash']); return $x;
}
function user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!user()) redirect(BASE_URL . '/index.php'); }
function require_role(string ...$roles): void {
    require_login();
    if (!in_array(user()['rol'],$roles,true)) { http_response_code(403); exit('Acceso denegado'); }
}
function url(string $path=''): string { return BASE_URL . '/' . ltrim($path, '/'); }
function header_html(string $title): void {
    $u=user();
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>'.e($title).' - SaludUSM</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="'.url('CSS/style.css').'" rel="stylesheet"></head><body>';
    if($u){
        echo '<nav class="navbar navbar-dark bg-primary navbar-expand-lg"><div class="container container-main">';
        echo '<a class="navbar-brand" href="'.url($u['rol']==='PACIENTE'?'paciente/dashboard.php':($u['rol']==='MEDICO'?'medico/dashboard.php':'admin/dashboard.php')).'">SaludUSM</a><div class="navbar-nav">';
        if($u['rol']==='PACIENTE') echo '<a class="nav-link" href="'.url('paciente/buscar.php').'">Buscar</a><a class="nav-link" href="'.url('paciente/mis_citas.php').'">Mis citas</a><a class="nav-link" href="'.url('paciente/agendar.php').'">Agendar</a><a class="nav-link" href="'.url('paciente/historial.php').'">Historial</a>';
        if($u['rol']==='MEDICO') echo '<a class="nav-link" href="'.url('medico/agenda.php').'">Agenda</a><a class="nav-link" href="'.url('medico/historial_medico.php').'">Historial pacientes</a>';
        if($u['rol']==='ADMIN') echo '<a class="nav-link" href="'.url('admin/admin.php').'">Administracion</a><a class="nav-link" href="'.url('admin/centros.php').'">Centros</a><a class="nav-link" href="'.url('admin/medicos.php').'">Medicos</a><a class="nav-link" href="'.url('admin/buscar_citas.php').'">Buscar citas</a>';
        echo '<a class="nav-link" href="'.url(($u['rol']==='PACIENTE'?'paciente/perfil.php':($u['rol']==='MEDICO'?'medico/perfil.php':'admin/perfil.php'))).'">Perfil</a><a class="nav-link" href="'.url('logout.php').'">Salir</a></div></div></nav>';
    }
    echo '<main class="container container-main py-4">';
    if($m=flash()) echo '<div class="alert alert-info">'.e($m).'</div>';
}
function footer_html(): void { echo '</main></body></html>'; }
