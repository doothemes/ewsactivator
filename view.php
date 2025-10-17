<?php

// Archivo de Inicialización
require_once __DIR__ . '/init.php';


function exit_with_error_code(string $code): void {
    header('Content-Type: text/plain; charset=utf-8');
    exit($code);
}

// Establecer la clave de licencia desde el parámetro GET
$license = trim($_GET['key'] ?? '');

// Validar la clave de licencia
if($license === '' || !preg_match('/^[a-zA-Z0-9_-]{6,60}$/', $license)){
    exit_with_error_code('error/invalid_or_missing_license');
}

// Obtener datos de la licencia desde PocketBase
$license_data = PocketBase::get_license($license);

// Validar respuesta de PocketBase
if(empty($license_data) || !is_array($license_data)){
    exit_with_error_code('error/invalid_response_from_pocketbase');
}

// Verificar si la licencia es válida
if(!isset($license_data['data']['id'])){
    exit_with_error_code($license_data['message'] ?? 'error/license_not_found_or_invalid');
}

header('Content-Type: application/json');
exit(json_encode($license_data));