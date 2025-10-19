<?php

// Establecer tipo de contenido
header('Content-Type: application/json');

// Incluir archivo de inicialización
require_once __DIR__ . '/init.php';
/**
 * Clase AjaxHandler
 * Maneja las solicitudes AJAX para autenticación y gestión de licencias.
 * @version 2.0
 * @author ActivatedWin
 */
class AjaxHandler{

    // Lista de acciones permitidas
    private $allowed_actions;


    /**
     * Constructor
     * Inicializa la lista de acciones permitidas.
     */
    public function __construct(){
        $this->allowed_actions = [
            'auth_recover_password',
            'auth_validate_otp',
            'auth_change_password',
            'auth_login',
            'auth_logout',
            'license_creator',
            'get_license'
        ];
    }

    /**
     * Ejecuta la acción solicitada
     * @return void
     */
    public function run(): void {
        // Obtener la acción solicitada
        $action = $_REQUEST['action'] ?? null;
        // Verificar si se proporcionó una acción
        if(!$action){
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Establece una acción válida.']);
            return;
        }
        // Sanitizar la acción para permitir solo caracteres alfanuméricos y guiones bajos
        $action = preg_replace('/[^a-zA-Z0-9_]/', '', $action); 
        // Verificar si la acción válida, tiene que existe como método y estar en la lista de permitidas
        if($action && method_exists($this, $action) && in_array($action, $this->allowed_actions)){
            $this->$action();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Acción no válida o no esta permitida.']);
        }
    }

    /**
     * Inicia el proceso de recuperación de contraseña
     * Genera un código OTP por 30 minutos y lo asocia al usuario.
     * @return void
     */
    private function auth_recover_password(){
        // Verificar si el usuario ya está autenticado
        if(is_logged_in() === true){
            echo json_encode(['success' => false, 'message' => 'Ya has iniciado sesión.']);
            exit;
        }
        // Obtener y sanitizar el nombre de usuario
        $username = trim($_REQUEST['username'] ?? '');
        // Validar el nombre de usuario
        if($username === ''){
            echo json_encode(['success' => false, 'message' => 'Falta el nombre de usuario.', 'field' => false]);
            exit;
        }
        // Cargar datos de usuarios
        $users_data = json_decode(file_get_contents(__DIR__.'/storage/users.json'), true);
        // Buscar el usuario por nombre de usuario o correo electrónico
        $get_user = find_user($username, $users_data);
        // Verificar si se encontró el usuario
        if(empty($get_user)){
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.', 'field' => 'username']);
            exit;
        }
        // Función para generar un OTP de 6 dígitos
        $set_otp = generate_otp();
        // Establecer el nombre de usuario desde la clave del array
        $set_username = array_key_first($get_user);
        // Actualizar el usuario con el OTP y la fecha de expiración (30 minutos)
        if(update_user($set_username, ['otp' => $set_otp, 'otp_expiration' => (time()+1800), 'otp_verified' => false], $users_data) === false){
            echo json_encode(['success' => false, 'message' => 'Error al generar el código OTP.']);
            exit;
        }
        // Obtener el correo electrónico y nombre del usuario
        $user_email = $get_user[$set_username]['email'] ?? '';
        $user_name  = $get_user[$set_username]['name']  ?? '';
        // Establecer datos para el correo electrónico
        $mail_data = [
            'otp' => $set_otp,
            'name' => $user_name,
            'username' => $set_username,
            'email' => $user_email
        ];
        // Componer asunto y cuerpo del correo
        $mail_subject = mail_tagger('Restablecer contraseña: {otp}', $mail_data);
        $mail_body = mail_tagger(mail_message('reset_password'), $mail_data);
        // Enviar el correo electrónico con el código OTP
        if(ews_send_mail($user_email, $mail_subject, $mail_body) === false){
            echo json_encode(['success' => false, 'message' => 'Error al enviar el correo electrónico con el código OTP.']);
            exit;
        }
        // Responder con éxito
        echo json_encode([
            'success' => true,
            'message' => 'Código OTP generado exitosamente.',
            'data' => [
                'username' => $set_username,
                'userkey' => $get_user[$set_username]['userkey']  ?? ''
            ]
        ]);
    }

    /**
     * Valida el código OTP para la recuperación de contraseña
     * @return void
     */
    private function auth_validate_otp(){
        // Verificar si el usuario ya está autenticado
        if(is_logged_in()){
            echo json_encode(['success' => false, 'message' => 'Ya has iniciado sesión.']);
            exit;
        }
        // Obtener y sanitizar el nombre de usuario y el código OTP
        $userkey = trim($_REQUEST['userkey'] ?? '');
        $username = trim($_REQUEST['username'] ?? '');
        $otp = trim($_REQUEST['otp'] ?? '');
        // Validar el nombre de usuario y el código OTP
        if($username === '' || $otp === ''){
            echo json_encode(['success' => false, 'message' => 'Faltan el nombre de usuario o el código OTP.']);
            exit;
        }
        // Cargar datos de usuarios
        $users_data = json_decode(file_get_contents(__DIR__ . '/storage/users.json'), true);
        // Buscar el usuario por nombre de usuario o correo electrónico
        $get_user = find_user($username, $users_data);
        // Verificar si se encontró el usuario
        if(empty($get_user)){
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            exit;
        }
        // Obtener el nombre de usuario desde la clave del array
        $found_username = array_key_first($get_user);
        $user_data = $get_user[$found_username];
        // Obtener el OTP y la fecha de expiración del usuario
        $get_otp = $user_data['otp'] ?? 'none';
        $get_otp_expiration = $user_data['otp_expiration'] ?? 0;
        // Validar el OTP y su expiración
        if($get_otp === 'none' || $get_otp_expiration === 0){
            echo json_encode(['success' => false, 'message' => 'No existe un código OTP activo.']);
            exit;
        }
        // Verificar si el OTP es correcto
        if($otp !== $get_otp){
            echo json_encode(['success' => false, 'message' => 'Código OTP incorrecto.']);
            exit;
        }
        // Verificar si el OTP ha expirado
        if(time() > $get_otp_expiration){
            if(!update_user($found_username, ['otp' => 'none', 'otp_expiration' => 0, 'otp_verified' => false], $users_data)){
                echo json_encode(['success' => false, 'message' => 'Error al validar el código OTP. Genera uno nuevo.']);
                exit;
            }else{
                echo json_encode(['success' => false, 'message' => 'El código OTP ha expirado. Genera uno nuevo.']);
                exit;
            }
        }
        // Marcar el OTP como verificado y eliminarlo
        if(!update_user($found_username, ['otp' => 'none', 'otp_expiration' => 0, 'otp_verified' => true], $users_data)){
            echo json_encode(['success' => false, 'message' => 'Error al validar el código OTP.']);
            exit;
        }
        // Responder con éxito
        echo json_encode([
            'success'   => true,
            'message'   => 'Código OTP validado exitosamente.',
            'username'  => $found_username,
            'next_step' => 'reset_password'
        ]);
    }
    
    /**
     * Cambia la contraseña del usuario después de validar el OTP
     * @return void
     */
    private function auth_change_password(){
        // Verificar si el usuario ya está autenticado
        if(is_logged_in() === true){
            echo json_encode(['success' => false, 'message' => 'Ya has iniciado sesión.']);
            exit;
        }
        // Obtener y sanitizar los datos de entrada
        $userkey = trim($_REQUEST['userkey'] ?? '');
        $username  = trim($_REQUEST['username']  ?? '');
        $password1 = trim($_REQUEST['password1'] ?? '');
        $password2 = trim($_REQUEST['password2'] ?? '');
        // Validar los datos de entrada
        if($password1 === '' || $password2 === '' || $username === ''){
            echo json_encode(['success' => false, 'message' => 'Faltan las nuevas contraseñas.', 'field' => false]);
            exit;
        }
        // Validar que las contraseñas coincidan
        if($password1 !== $password2){
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.', 'field' => 'password2']);
            exit;
        }
        // Validar la longitud mínima de la contraseña
        if(strlen($password1) < 6){
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres.', 'field' => 'password1']);
            exit;
        }
        // Cargar datos de usuarios
        $users_data = json_decode(file_get_contents(__DIR__.'/storage/users.json'), true);
        // Buscar el usuario por nombre de usuario o correo electrónico
        $get_user = find_user($username, $users_data);
        // Verificar si se encontró el usuario
        if(empty($get_user)){
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            exit;
        }
        // Obtener el nombre de usuario desde la clave del array
        $found_username = array_key_first($get_user);
        $user_data = $get_user[$found_username];
        // Verificar si el OTP ha sido verificado
        if(!isset($user_data['otp_verified']) || $user_data['otp_verified'] !== true){
            echo json_encode(['success' => false, 'message' => 'No se ha verificado el código OTP.']);
            exit;
        }
        // Actualizar la contraseña del usuario y marcar OTP como no verificado
        if(!update_user($found_username, ['password' => password_hash($password1, PASSWORD_DEFAULT), 'otp_verified' => false], $users_data)){
            echo json_encode(['success' => false, 'message' => 'Error al cambiar la contraseña.']);
            exit;
        }
        // Responder con éxito
        echo json_encode(['success' => true, 'message' => 'Contraseña cambiada exitosamente. Ya puedes iniciar sesión con tu nueva contraseña.']);
    }

    /**
     * Maneja el inicio de sesión de autenticación
     * Crea una sesión para el usuario si las credenciales son correctas.
     * La session debe ser almacenada en el servidor.
     * @return void
     */
    private function auth_login(){
        // Iniciar sesión si no está iniciada
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        // Establecer credenciales
        $username = trim($_REQUEST['username'] ?? '');
        $password = trim($_REQUEST['password'] ?? '');
        // Validar credenciales
        if($username === '' || $password === ''){
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Faltan credenciales.', 'field' => false]);
            exit;
        }
        // Cargar datos de usuarios
        $users_data = json_decode(file_get_contents(__DIR__.'/storage/users.json'), true);
        // Verificar si el usuario existe
        if(!array_key_exists($username, $users_data)){
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.', 'field' => 'username']);
            exit;
        }
        // Verificar la contraseña
        if(!password_verify($password, $users_data[$username]['password'])){
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.', 'field' => 'password']);
            exit;
        }
        // Establecer la fecha y hora actual
        $date_now = date('Y-m-d H:i:s');
        // Establecer la sesión
        $_SESSION['ews_auth'] = [
            'username' => $username,
            'name' => $users_data[$username]['name'] ?? $username,
            'surname' => $users_data[$username]['surname'] ?? '',
            'login_at' => $date_now,
            'token' => bin2hex(random_bytes(32)) // Token de sesión
        ];
        // Guardar la última fecha de inicio de sesión y asegurar que OTP esté desactivado
        if(!update_user($username, ['last_login' => $date_now, 'otp' => 'none', 'otp_expiration' => 0, 'otp_verified' => false], $users_data)){
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la última fecha de inicio de sesión.']);
            exit;
        }
        // Responder con éxito
        echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso.', 'data' => $_SESSION['ews_auth']]);
    }

    /**
     * Maneja el cierre de sesión de autenticación
     * Destruye la sesión del usuario.
     * @return void
     */
    private function auth_logout(){
        // Iniciar sesión si no está iniciada
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        // Destruir la sesión
        session_unset();
        session_destroy();
        // Responder con éxito
        echo json_encode(['success' => true, 'message' => 'Cierre de sesión exitoso.']);
    }

    /**
     * Crea una nueva licencia en PocketBase
     * Requiere que el usuario esté autenticado.
     * @return void
     */
    private function license_creator(){
        // Verificar si el usuario está autenticado
        if(is_logged_in() === false){
            echo json_encode(['success' => false, 'message' => 'Requiere autenticación.']);
            exit;
        }
        // Obtener y sanitizar correo electrónico
        $user_email = trim($_REQUEST['email_address'] ?? '');
        // Validar correo electrónico
        if($user_email === '' || !filter_var($user_email, FILTER_VALIDATE_EMAIL)){
            echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
            exit;
        }
        // Obtener y sanitizar productos seleccionados
        $microsoft_office = trim($_REQUEST['microsoft_office'] ?? '');
        $microsoft_windows = trim($_REQUEST['microsoft_windows'] ?? '');
        // Inicializar array de productos
        $products = [];
        // Agregar productos al array
        $products[] = $microsoft_office ? 'OFFICE' : '';
        $products[] = $microsoft_windows ? 'WINDOWS' : '';
        // Datos para calcular totales
        $subtotal = floatval($_REQUEST['subtotal'] ?? 0);
        $total_discount = floatval($_REQUEST['total_discount'] ?? 0);
        $total_payment = floatval($_REQUEST['total_payment'] ?? 0);
        $total_expenditure = floatval($_REQUEST['total_expenditure'] ?? 0);
        // Validar totales
        if($subtotal <= 0){
            echo json_encode(['success' => false, 'message' => 'El subtotal debe ser mayor a cero.']);
            exit();
        }
        if($total_discount < 0 || $total_discount > $subtotal){
            echo json_encode(['success' => false, 'message' => 'El descuento no puede ser negativo ni mayor al subtotal.']);
            exit();
        }
        if($total_payment <= 0){
            echo json_encode(['success' => false, 'message' => 'El total de pago no puede ser cero o negativo.']);
            exit();
        }
        if($total_payment > $subtotal){
            echo json_encode(['success' => false, 'message' => 'El total de pago no puede superar el subtotal.']);
            exit();
        }
        if($total_expenditure < 0 || $total_expenditure > $total_payment) {
            echo json_encode(['success' => false, 'message' => 'El gasto no puede ser negativo ni mayor al total del pago.']);
            exit();
        }
        // Crear nueva licencia en PocketBase
        $new_license = PocketBase::add_license([
            'email' => $user_email,
            'phone' => trim($_REQUEST['phone_number'] ?? ''),
            'firstname' => trim($_REQUEST['firstname'] ?? ''),
            'lastname' => trim($_REQUEST['lastname'] ?? ''),
            'products' => $products,
            'windows_edition' => $microsoft_windows,
            'office_edition' => $microsoft_office,
            'currency' => trim($_REQUEST['currency'] ?? 'PEN'),
            'payment_method' => trim($_REQUEST['payment_method'] ?? 'YAPE'),
            'payment_description' => trim($_REQUEST['payment_description'] ?? ''),
            'subtotal' => $subtotal,
            'total_discount' => $total_discount,
            'total_payment' => $total_payment,
            'total_expenditure' => $total_expenditure,
            'total_profit' => floatval($total_payment - $total_expenditure),
            'count_requests' => 1,
            'count_activations' => 0,
            'limit_activations' => intval($_REQUEST['limit_activations'] ?? 10),
            'active' => true,
        ]);
        // Validar respuesta de PocketBase
        if(empty($new_license) || !is_array($new_license) || !isset($new_license['data']['id'])){
            $message = $new_license['message'] ?? 'Error al crear la licencia.';
            echo json_encode(['success' => false, 'message' => $message]);
            exit;
        }
        // Responder con éxito
        echo json_encode($new_license);
    }

    /**
     * Obtiene los datos de una licencia desde PocketBase
     * Requiere que el usuario esté autenticado.
     * @return void
     */
    private function get_license(){
        // Verificar si el usuario está autenticado
        if(is_logged_in() === false){
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Requiere autenticación.']);
            exit;
        }
        // Obtener y sanitizar clave de licencia
        $license_key = trim($_REQUEST['key'] ?? '');
        // Validar clave de licencia
        if($license_key === ''){
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Falta la clave de licencia.']);
            exit;
        }
        // Obtener y validar la licencia en PocketBase
        echo json_encode(PocketBase::get_license($license_key));
    }
}

// Ejecutar el manejador AJAX
$ajax = new AjaxHandler();
$ajax->run(); // Llama al método run para procesar la solicitud
