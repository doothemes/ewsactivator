<?php

// Require archivos necesarios
require_once __DIR__ . '/init.php';

// Redirigir según el estado de autenticación
if(is_logged_in() === false){
    header('Location: /auth/login'); // Redirigir a la página de login
    exit;
}else{
    header('Location: /admin/dashboard'); // Redirigir al panel de administración
    exit;
}