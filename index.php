<?php

// Archivo de Inicialización
require_once __DIR__ . '/init.php';
// Determinar el archivo de autenticación basado en la acción GET
$auth_action = trim($_GET['action'] ?? 'login');
$auth_file = __DIR__ . '/views/auth/' .$auth_action. '.php';
// Título de la página y clase del body
$title = 'Autenticación';
$bodyclass = 'auth '.$auth_action;
// Redirigir según el estado de autenticación
if(is_logged_in() === false){
    require __DIR__ . '/views/header.php';
    if(file_exists($auth_file)) {
        require $auth_file;
    } else {
        require __DIR__ . '/views/auth/login.php';
    }
    require __DIR__ . '/views/footer.php';
}else{
    header('Location: /admin/dashboard'); // Redirigir al panel de administración
    exit;
}