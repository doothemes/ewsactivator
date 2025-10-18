<?php
/**
 * Archivo de inicialización del proyecto EWSActivator
 * Configuraciones generales, constantes y carga de dependencias
 */

// Establecer la zona horaria
date_default_timezone_set('America/Lima');
// Establecer version
define('EWS_VERSION', '1.0.11');
// Clave pública y privada para cifrado/descifrado
define('EWS_INSURANCE_CHAIN_PUBLIC', 'JPeW3YEuX7vPCDvKMbbnCPhvJsebn3NxwL9Dh61c8obg0Pd8Ciav7cXkRktFT66K');
define('EWS_INSURANCE_CHAIN_PRIVATE', 'GBxMAGZ3mT85dAdZzmCbe7dLUUk6EDp7b24Qa7iJdhxj7BeE54rGb3edgBqBjXrp');
// Configuraciones generales
define('EWS_SESSION_TIMEOUT', 15 * 60); // 15 minutos en segundos
define('EWS_MAX_LOGIN_ATTEMPTS', 5); // Máximo 5 intentos de login
define('EWS_LOGIN_LOCKOUT_DURATION', 15 * 60); // Bloqueo de 15 minutos en segundos
define('EWS_OTP_EXPIRATION', 10 * 60); // OTP válido por 10 minutos en segundos
define('EWS_PASSWORD_RESET_EXPIRATION', 30 * 60); // Enlace de restablecimiento válido por 30 minutos en segundos
define('EWS_COOKIE_EXPIRATION', 30 * 24 * 60 * 60); // Cookie válida por 30 días en segundos
define('EWS_REMEMBER_ME_EXPIRATION', 30 * 24 * 60 * 60); // "Recordarme" válido por 30 días en segundos
define('EWS_PASSWORD_MIN_LENGTH', 8); // Longitud mínima de la contraseña
define('EWS_PASSWORD_REQUIRE_SPECIAL', true); // Requiere caracteres especiales en la contraseña
define('EWS_ROOT_PATH', __DIR__); // Ruta raíz del proyecto
define('EWS_INCLUDES_PATH', EWS_ROOT_PATH . '/includes'); // Ruta de includes
define('EWS_ASSETS_PATH', EWS_ROOT_PATH . '/assets'); // Ruta de assets
define('EWS_VIEWS_PATH', EWS_ROOT_PATH . '/views'); // Ruta de vistas
define('EWS_STORAGE_PATH', EWS_ROOT_PATH . '/storage'); // Ruta de almacenamiento
define('EWS_LOGS_PATH', EWS_STORAGE_PATH . '/logs'); // Ruta de logs
define('EWS_SESSIONS_PATH', EWS_STORAGE_PATH . '/sessions'); // Ruta de logs
define('EWS_USERS_FILE', EWS_STORAGE_PATH . '/users.json'); // Archivo de usuarios
define('EWS_SETTINGS_FILE', EWS_STORAGE_PATH . '/settings.json'); // Archivo de configuración

// Configuración de PHPMailer
define('EWS_SMTP_HOST', 'smtp.gmail.com');
define('EWS_SMTP_PORT', 587);
define('EWS_SMTP_USERNAME', 'plQPBiA+ulnOVhW2UHQFdWjLkYxkjWHr+TU4EXqpVEQ=');
define('EWS_SMTP_PASSWORD', 'oom3103AbZBEvHNlHzb44AjGN2RU+Ib8tZDd5xXRMNWykWrillFU71+4XK8yOuDF');
define('EWS_SMTP_ENCRYPTION', 'tls');
define('EWS_SMTP_FROM_EMAIL', 'isp@ews.pe');
define('EWS_SMTP_FROM_NAME', 'EWS Networks');

// PocketBase Config
define('EWS_POCKETBASE_PORT', 8090);
define('EWS_POCKETBASE_LOCAL', '127.0.0.1');
define('EWS_POCKETBASE_API_URL', 'https://pb.ews.pe/');
define('EWS_POCKETBASE_API_USERNAME', 'uQdLI7fsDu+8MAH53m9tr6sdKbDHiIEHEH036Nnop+4=');
define('EWS_POCKETBASE_API_PASSWORD', 'losmpZFw8+MXArXtMLiwVINnaeveCR/KjIWttRNHAHg=');

// Cargar Composer autoload si existe
require_once EWS_INCLUDES_PATH . '/PHPMailer/Exception.php';
require_once EWS_INCLUDES_PATH . '/PHPMailer/PHPMailer.php';
require_once EWS_INCLUDES_PATH . '/PHPMailer/SMTP.php';

// Incluir archivos necesarios
require_once EWS_INCLUDES_PATH . '/functions.php'; 
require_once EWS_INCLUDES_PATH . '/httpclient.php'; 
require_once EWS_INCLUDES_PATH . '/pocketbase.php';