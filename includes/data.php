<?php
/**
 * data.php
 * Handles AJAX requests to add new license records to /storage/licenses.json
 * 
 * Author: Erick Meza
 * Date: 2025-10-13
 */

header('Content-Type: application/json');

// Path to JSON storage
$storage_path = __DIR__ . '/storage/licenses.json';

// Ensure file exists
if (!file_exists($storage_path)) {
    file_put_contents($storage_path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Load current licenses
$licenses = json_decode(file_get_contents($storage_path), true);
if (!is_array($licenses)) {
    $licenses = [];
}

// Process only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

// Sanitize and validate input
$name    = trim($_POST['name'] ?? '');
$surname = trim($_POST['surname'] ?? '');
$email   = trim($_POST['email'] ?? '');

if ($name === '' || $surname === '' || $email === '') {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'El correo electrónico no es válido.']);
    exit;
}

// Generate unique ID and timestamps
$license_id = md5(uniqid($email, true));
$now = date('Y-m-d H:i:s');

// Build new record
$licenses[$license_id] = [
    'name'       => $name,
    'surname'    => $surname,
    'email'      => $email,
    'created_at' => $now,
    'updated_at' => $now
];

// Save JSON file
file_put_contents($storage_path, json_encode($licenses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Response
echo json_encode([
    'status'  => 'success',
    'message' => 'Licencia agregada correctamente.',
    'id'      => $license_id
]);
exit;
