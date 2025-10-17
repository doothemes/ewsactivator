<?php

// Require archivos necesarios
require_once __DIR__ . '/init.php';
// Determinar el archivo de administración basado en la acción GET
$admin_action = trim($_GET['action'] ?? 'dashboard');
$admin_file = __DIR__ . '/views/admin/' .$admin_action. '.php';
// Título de la página y clase del body
$title = 'Administración';
$bodyclass = 'admin '.$admin_action;
// Redirigir según el estado de autenticación
if(is_logged_in() === true){
    require __DIR__ . '/views/header.php';
    if(file_exists($admin_file)) {
        require $admin_file;
    } else {
        require __DIR__ . '/views/admin/dashboard.php';
    }
    require __DIR__ . '/views/footer.php';
}else{
    header('Location: /auth/login'); // Redirigir al panel de administración
    exit;
}
