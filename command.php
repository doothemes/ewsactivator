<?php
/**
 * license.php
 *
 * Proxy para validar licencias y obtener el script de despliegue
 * desde PocketBase y https://get.activated.win/
 * @version 2.0
 */

// Inicializar la respuesta como texto plano 
header('Content-Type: text/plain; charset=utf-8');

// Obtener User-Agent de la solicitud
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

// Filtrar User-Agent para permitir solo PowerShell
if(stripos($user_agent, 'PowerShell') === false){
    http_response_code(403);
    exit('error/forbidden');
}

// Incluir archivo de inicialización
require_once __DIR__ . '/init.php';

/**
 * Salir con un mensaje y código de estado HTTP
 * @param string $message Mensaje a mostrar
 * @param string $color Color del mensaje (por defecto: 'Red')
 * @param int $code Código de estado HTTP (por defecto: 400)
 * @return void
 */
function exit_with_message(string $message, string $color = 'Red', int $code = 200): void {
    http_response_code($code);
    $safeMessage = str_replace(['"', "'"], '', $message);
    echo "Write-Host \"{$safeMessage}\" -ForegroundColor {$color}\n";
    exit;
}

// Establecer la clave de licencia desde el parámetro GET
$license = trim($_GET['key'] ?? '');

// Validar la clave de licencia
if($license === '' || !preg_match('/^[a-zA-Z0-9_-]{6,60}$/', $license)){
    exit_with_message('Falta la clave de licencia o el formato no es válido.');
}

// Obtener datos de la licencia desde PocketBase
$license_data = PocketBase::activated_license($license);

// Validar respuesta de PocketBase
if(empty($license_data) || !is_array($license_data)){
    exit_with_message('Respuesta no válida de PocketBase.');
}

// Verificar si la licencia es válida
if(!isset($license_data['data']['id'])){
    $message = $license_data['message'] ?? 'Licencia no encontrada o inválida.';
    exit_with_message($message);
}

// Extraer nombre y correo electrónico del usuario
$name  = $license_data['data']['name']  ?? 'John';
$email = $license_data['data']['email'] ?? 'johndoe@outlook.com';

// Iniciar el comando de despliegue PowerShell
$deploy_command = '';
$deploy_command .= "Write-Host ''\n";
$deploy_command .= "Write-Host '{$name}, gracias por tu compra:' -NoNewline\n";
$deploy_command .= "Write-Host ' Licencia verificada!' -ForegroundColor Green\n";
$deploy_command .= "Write-Host ''\n";
$deploy_command .= "Write-Host 'Microsoft Account: ' -NoNewline\n";
$deploy_command .= "Write-Host '{$email}' -ForegroundColor Yellow\n\n";

// Preparar HttpClient: sin proxies externos, opcionalmente configurar interfaces locales si el servidor tiene varias IP.
// Ejemplo: ['203.0.113.10', '203.0.113.11'] — reemplazar con las IP asignadas a su servidor si están disponibles.
$localInterfaces = []; // <-- reemplazar con las IP asignadas a su servidor si están disponibles
$useTor = false; // establecer en true para usar Tor (requiere que Tor esté instalado y en ejecución localmente)

$client = new HttpClient($localInterfaces, $useTor);

// Opciones de solicitud:
// - spoof_xff: Agregar cabecera X-Forwarded-For con IP aleatoria
// - retries: Numero de reintentos en caso de error (con retroceso exponencial)
$options = [
    'spoof_xff' => true,
    'retries' => 2,
];

// Ejecutar la solicitud al script remoto
$result = $client->request('https://get.activated.win/', $options);

// Manejar errores de conexión
if (!$result['success']) {
    // PowerShell-friendly error output
    $msg = str_replace('"', '', $result['error']);
    exit_with_message("Connection failed: {$msg}");
}

// Manejar códigos de estado HTTP no exitosos
http_response_code($result['http_code']);

// Parametros no deseados a eliminar del script
$patterns = [
    '/#\s*This\s+script\s+is\s+hosted\s+on\s+<b>https?:\/\/get\.activated\.win<\/b>\s+for\s+<b>https?:\/\/massgrave\.dev<\/b>\s*(?:<br\s*\/?>\s*){1,2}/i',
    '/#\s*Having\s+trouble\s+launching\s+this\s+script\?\s*Check\s+<a\s+href="https?:\/\/massgrave\.dev">https?:\/\/massgrave\.dev<\/a>\s+for\s+help\.\s*(?:<hr\s*\/?>\s*<pre\s*>)?/i',
    '/Write-Host\s+[\'"]Need\s+help\?\s*Check\s*our\s*homepage:\s*[\'"]\s*-NoNewline/i',
    '/Write-Host\s+[\'"]https?:\/\/massgrave\.dev[\'"]\s*-ForegroundColor\s+Green/i',
];

// Eliminar los patrones no deseados del script
$deploy_command .= preg_replace($patterns, '', $result['body'] ?? '');

// Agregar el script remoto al comando de despliegue
echo trim($deploy_command);