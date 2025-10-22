<?php
// PHPMailer autoload
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Cifrar y descifrar cadenas de texto usando AES-256-CBC
 * @param string $string La cadena a cifrar o descifrar.
 * @return string La cadena cifrada o descifrada.
 */
function encode_secure_string(string $string = ''): string {
    // Retornar cadena vacía si la entrada está vacía
    if($string === ''){
        return '';
    }
    // Derivar la clave de 32 bytes usando SHA-256
    $key = hash('sha256', EWS_INSURANCE_CHAIN_PRIVATE, true);
    // Generarar un IV aleatorio de 16 bytes
    $iv = openssl_random_pseudo_bytes(16);
    // Encriptar usando AES-256-CBC
    $encrypted = openssl_encrypt($string, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    // Retornar la cadena cifrada en Base64 (IV + ciphertext)
    return base64_encode($iv . $encrypted);
}

/**
 * Descifrar una cadena cifrada con AES-256-CBC
 * @param string $encoded La cadena cifrada en Base64.
 * @return string La cadena descifrada.
 */
function decode_secure_string(string $encoded = ''): string {
    // Retornar cadena vacía si la entrada está vacía
    if($encoded === ''){
        return '';
    }
    // Decodificar Base64
    $data = base64_decode($encoded);
    // Extraer IV y texto cifrado
    $iv = substr($data, 0, 16);
    $ciphertext = substr($data, 16);
    // Derivar la clave de 32 bytes usando SHA-256
    $key = hash('sha256', EWS_INSURANCE_CHAIN_PRIVATE, true);
    // Decifrar usando AES-256-CBC
    $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    // Retornar cadena vacía si la descifrado falla
    return $decrypted === false ? '' : $decrypted;
}

/**
 * Generar un OTP (One-Time Password) numérico
 *
 * @param int $length La longitud del OTP a generar. Por defecto es 6.
 * @return string El OTP generado.
 */
function generate_otp(int $length = 6): string {
    $characters = '0123456789';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $otp;
}


/**
 * Buscar usuario por username o email
 *
 * @param string $identifier El nombre de usuario o email a buscar.
 * @param array $users_data El esquema de datos de usuarios.
 * @return array Los datos del usuario si se encuentra, o un array vacío si no se encuentra.
 */
function find_user(string $identifier, array $users_data): array {
    // Buscar por username directo
    if(isset($users_data[$identifier])){
        return [$identifier => $users_data[$identifier]];
    }
    // Buscar por email dentro del esquema
    foreach ($users_data as $username => $data) {
        if(isset($data['email']) && strtolower($data['email']) === $identifier){
            return [$username => $data];
        }
    }
    // Si no se encuentra
    return [];
}

/**
 * Actualizar datos del usuario y guardar en el archivo JSON
 * @param string $username El nombre de usuario a actualizar.
 * @param array $new_data Los nuevos datos a actualizar.
 * @param array &$users_data El esquema de datos de usuarios (pasado por referencia).
 * @return bool True si la actualización fue exitosa, false en caso contrario.
 */
function update_user(string $username, array $new_data, array &$users_data): bool {
    if(!isset($users_data[$username])){
        return false; // Usuario no existe
    }
    // Actualizar datos del usuario
    foreach ($new_data as $key => $value) {
        $users_data[$username][$key] = $value;
    }
    // Guardar cambios en el archivo JSON
    $json_data = json_encode($users_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if(file_put_contents(__DIR__.'/../storage/users.json', $json_data) === false){
        return false; // Error al guardar
    }
    return true;
}


/**
 * Verificar si el usuario está autenticado
 * @return bool
 */
function is_logged_in(): bool {
    // Iniciar sesión si no está iniciada
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    // Verificar si el usuario está autenticado
    if(isset($_SESSION['ews_auth']) && isset($_SESSION['ews_auth']['username'])){
        return true;
    }
    return false;
}

/**
 * Obtener la dirección IP del cliente
 * @param bool $allowPrivate Si es true, permite direcciones IP privadas/reservadas
 * @return string|null La dirección IP o null si no se encuentra
 */
function get_ip_address(bool $allowPrivate = false): ?string{
    // Candidate header keys to inspect (order matters: prefer more specific headers first).
    $keys = [
        'HTTP_CF_CONNECTING_IP', // Cloudflare
        'HTTP_X_REAL_IP',        // Nginx X-Real-IP
        'HTTP_X_FORWARDED_FOR',  // Standard X-Forwarded-For (may contain a list)
        'HTTP_X_FORWARDED',      // alternative
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR'
    ];
    // Validation flags: by default exclude private/reserved ranges to prefer public IPs.
    $filterFlags = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6;
    // When using filter_var we use FILTER_VALIDATE_IP and add flags conditionally.
    // We cannot pass both IPV4 and IPV6 flags to filter_var; instead use FILTER_VALIDATE_IP with the range flags.
    $rangeFlags = $allowPrivate
        ? 0
        : FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
    foreach ($keys as $key){
        if(empty($_SERVER[$key])){
            continue;
        }
        $value = trim((string) $_SERVER[$key]);
        // X-Forwarded-For may contain a comma separated list of IPs: client, proxy1, proxy2...
        // We want the left-most valid IP (the original client), but skip private/reserved unless allowPrivate=true.
        if(strpos($value, ',') !== false){
            $parts = array_map('trim', explode(',', $value));
        } else {
            $parts = [$value];
        }

        foreach ($parts as $ipCandidate) {
            // remove possible ports (e.g. "1.2.3.4:1234")
            if (strpos($ipCandidate, ':') !== false && substr_count($ipCandidate, ':') === 1) {
                // IPv4 with port
                $ipCandidate = strstr($ipCandidate, ':', true);
            }

            // Validate IP; apply range flags to disallow private/reserved by default
            if (filter_var($ipCandidate, FILTER_VALIDATE_IP, $rangeFlags) !== false) {
                return $ipCandidate;
            }

            // If allowPrivate is true, accept private/reserved addresses as a fallback
            if ($allowPrivate && filter_var($ipCandidate, FILTER_VALIDATE_IP) !== false) {
                return $ipCandidate;
            }
        }
    }

    // Nothing valid found
    return null;
}

/**
 * Enviar correo usando PHPMailer con SMTP de Gmail
 * @param string $to Dirección del destinatario
 * @param string $subject Asunto del correo
 * @param string $body Cuerpo del correo en HTML
 * @return bool True si el correo fue enviado exitosamente, false en caso contrario
 */
function ews_send_mail(string $to = '', string $subject = '', string $body = ''): bool{
    // Validar parámetros obligatorios
    if($to === '' || $subject === '' || $body === ''){
        return false;
    }
    // Definir la variable $mail dentro de la función
    $mail = new PHPMailer(true);
    // Configurar PHPMailer
    try {
        // Ajustes del Servidor SMTP
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = EWS_SMTP_HOST;
        $mail->Username = decode_secure_string(EWS_SMTP_USERNAME);
        $mail->Password = decode_secure_string(EWS_SMTP_PASSWORD);
        $mail->SMTPSecure = EWS_SMTP_ENCRYPTION;
        $mail->Port = EWS_SMTP_PORT;
        // Establecer al remitente y destinatario
        $mail->setFrom(EWS_SMTP_FROM_EMAIL, EWS_SMTP_FROM_NAME);
        $mail->addAddress($to);
        // Contenido del correo
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Subject = $subject;
        $mail->Body = $body;
        // Enviar el correo
        $mail->send();
        // Retornar una respuesta exitosa con true.
        return true;
    }catch(Exception $e){
        // En caso de error, retornar false.
        return false;
    }
    // Por defecto retornar false.
    return false;
}

/**
 * Reemplaza etiquetas dinámicas en el contenido de un correo electrónico.
 *
 * @param string $content Contenido del correo con etiquetas (por ejemplo: "Hola {name}, tu código es {otp}")
 * @param array $data Pares clave => valor para reemplazar las etiquetas personalizadas.
 * @param bool $include_common Si es true, incluye etiquetas comunes como {year}, {useragent}, {ipaddress}.
 * @param bool $sanitize Si es true, aplica htmlspecialchars() a los valores dinámicos.
 * @return string Contenido con las etiquetas reemplazadas.
 */
function mail_tagger(string $content = '', array $data = [], bool $include_common = true, bool $sanitize = true): string{
    // Sanitizar valores si corresponde
    if($sanitize){
        foreach ($data as &$value) {
            $value = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        }
    }
    // Agregar etiquetas comunes
    if ($include_common) {
        $data += [
            'year' => date('Y'),
            'useragent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'ipaddress' => get_ip_address(),
        ];
    }
    // Preparar formato {clave} => valor
    $replace_pairs = [];
    foreach ($data as $key => $value) {
        $replace_pairs['{' . $key . '}'] = $value;
    }
    // Reemplazar en una sola pasada
    $content = strtr($content, $replace_pairs);
    // Retornar el contenido limpio
    return trim($content);
}

/**
 * Cargar plantillas de correo desde archivos
 * @param string $file_name Nombre del archivo sin extensión
 * @param string $extension Extensión del archivo (por defecto: 'txt')
 * @return string Contenido del archivo o 'error/file_not_found' si no existe
 */
function mail_message(string $file_name = '', string $extension = 'html'): string{
    // Sanitizar el nombre del archivo
    $safe_name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $file_name);
    // Componer la ruta completa del archivo
    $file_path = rtrim(EWS_VIEWS_PATH)."/emails/{$file_name}.{$extension}";
    // Verificar si el archivo existe
    if(!is_file($file_path) || !is_readable($file_path)) {
        return 'error/file_not_found';
    }
    // Retornar el contenido del archivo
    return file_get_contents($file_path);
}


/**
 * Obtener la URL de Gravatar para un correo electrónico dado
 * @param string $email Dirección de correo electrónico
 * @return string URL de la imagen de Gravatar
 */
function get_gravatar_URL(string $email = ''): string {
    $email_hash = md5(strtolower(trim($email)));
    $gravatar_url = "https://www.gravatar.com/avatar/{$email_hash}?s=90&d=https%3A%2F%2Fi.imgur.com%2FQdNXN3p.png&r=g";
    return $gravatar_url;
}