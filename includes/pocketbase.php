 <?php

class PocketBase{

    private $url;
    private $api_identity;
    private $api_password;
    private $api_token;

    /**
     * Constructor to initialize the PocketBase API client with base URL and token.
     * 
     */
    public function __construct(){
        // Credenciales seguras para autenticación
        $this->api_identity = EWS_POCKETBASE_API_USERNAME;
        $this->api_password = EWS_POCKETBASE_API_PASSWORD;
        // Determinar URL base según disponibilidad local
        if($this->is_port_open(EWS_POCKETBASE_LOCAL, EWS_POCKETBASE_PORT)){
            $this->url = 'http://'.EWS_POCKETBASE_LOCAL.':'.EWS_POCKETBASE_PORT; // Usar conexión local si está disponible
        } else {
            $this->url = EWS_POCKETBASE_API_URL; // Usar URL pública
        }
    }

    /**
     * Verifica si un puerto está abierto en un host dado.
     *
     * @param string $host Host o IP a verificar.
     * @param int $port Puerto a verificar.
     * @param int $timeout Tiempo de espera en segundos.
     * @return bool True si el puerto está abierto, false en caso contrario.
     */
    private function is_port_open(string $host, int $port, int $timeout = 1): bool{
        // Intentar abrir una conexión de socket
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        // Si la conexión fue exitosa, el puerto está abierto
        if($connection){
            // cerrar la conexión
            fclose($connection); 
            // Puerto abierto
            return true;
        }
        // Puerto cerrado o no accesible
        return false;
    }

    /**
     * Obtiene y cachea el token de autenticación desde PocketBase.
     *
     * @return string|null Token de autenticación o null si falla.
     * @throws Exception Si no se puede obtener el token.
     */
    private function get_token(): ?string{
        // Validar credenciales configuradas
        if(empty($this->api_identity) || empty($this->api_password)){
            return null;
        }
        // Reutilizar token si ya está disponible
        if(!empty($this->api_token)){
            return $this->api_token;
        }
        // Decodificar credenciales seguras
        $auth_data = [
            'identity' => decode_secure_string($this->api_identity),
            'password' => decode_secure_string($this->api_password),
        ];
        // Usar correctamente la propiedad interna $this->url (no $pb->url)
        $url = rtrim($this->url, '/').'/api/collections/_superusers/auth-with-password';
        // Inicializar cURL
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($auth_data),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        // Manejo de errores cURL
        if($response === false || $http_code === 0){
            throw new Exception('Connection to PocketBase failed.');
        }
        // Decodificar respuesta
        $result = json_decode($response, true);
        // Validar estructura del resultado
        if($http_code !== 200 || empty($result['token'])){
            throw new Exception('Failed to obtain PocketBase token.');
        }
        // Guardar token para futuras solicitudes
        $this->api_token = $result['token'];
        return $this->api_token;
    }

    /**
     * Perform an authenticated HTTP request to the PocketBase API
     *
     * @param string $method   HTTP method (GET, POST, PATCH, DELETE)
     * @param string $endpoint API endpoint (starting with /api/)
     * @param array $data Request body (optional)
     * @return array Structured response
     */
    private function request(string $method, string $endpoint, array $data = []): array{
        // Obtener token de autenticación
        $this->get_token();
        // Ajustes de URL y cURL
        $url = rtrim($this->url, '/') . $endpoint;
        $ch  = curl_init($url);
        // Configuración base
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true, // cambia a false si usas certificado autofirmado
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer {$this->api_token}",
            ],
        ]);
        // Enviar datos si existen
        if(!empty($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        }
        // Ejecutar request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        // Manejo de errores de conexión
        if($err) {
            return ['error' => "cURL Error: {$err}"];
        }
        // Decodificar JSON
        $decoded = json_decode($response, true);
        // Si la respuesta no es JSON válida
        if(json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Respuesta JSON no válida de PocketBase.'];
        }
        // Evaluar código HTTP
        if($httpCode >= 400){
            return [
                'error' => $decoded['message'] ?? 'HTTP Error',
                'code' => $httpCode,
                'details' => $decoded,
            ];
        }
        // Éxito
        return $decoded;
    }

    /**
     * Add a new license record to PocketBase
     *
     * @param array $data  Data to be inserted (must include 'license' at least)
     * @return array Result status and data
     */
    public static function add_license(array $data = []): array{
        // Instanciar cliente PocketBase
        $pb = new self();
        // Validar datos mínimos
        if(empty($data)){
            return [
                'success' => false,
                'message' => 'No se proporcionaron datos.'
            ];
        }
        // Crear registro en PocketBase
        $response = $pb->request('POST','/api/collections/ews_windows_activator/records', $data);
        // Si hubo error HTTP o JSON inválido
        if(isset($response['status']) && isset($response['message'])){
            return [
                'success' => false,
                'message' => $response['message'] ?? 'Error desconocido de PocketBase.',
                'details' => $response
            ];
        }
        // Validar creación exitosa
        if(isset($response['id'])){
            return [
                'success' => true,
                'message' => 'Licencia creada y registrada.',
                'data' => $response
            ];
        }
        // Si llega aquí, algo no esperado ocurrió
        return [
            'success' => false,
            'message' => 'No se pudo crear la licencia.',
            'details' => $response
        ];
    }

    /**
     * Retrieves a license by its ID or code and increments its request counter
     *
     * @param string $license  License ID or code
     * @return array Response from PocketBase
     */
    public static function get_license(string $license = ''): array{
        // Instanciar cliente PocketBase
        $pb = new self();
        // Validar entrada
        if(empty($license)){
            return [
                'success' => false,
                'message' => 'Se requiere código de licencia.'
            ];
        }
        $license_data = $pb->request('GET', '/api/collections/ews_windows_activator/records/'.urlencode($license));
        // Manejo de errores del request GET
        if(!isset($license_data['id'])){
            return [
                'success' => false,
                'message' => $license_data['message'] ?? 'Licencia no encontrada.'
            ];
        }
        $counter_requests = (int)($license_data['count_requests'] ?? 0) + 1;
        // Actualizar contador de solicitudes
        $pb->request('PATCH', '/api/collections/ews_windows_activator/records/'.urlencode($license), ['count_requests' => $counter_requests]);
        // Éxito total
        return [
            'success' => true,
            'message' => 'Licencia recuperada exitosamente.',
            'data' => $license_data
        ];
    }


    /**
     * Activates a license and increments its usage counter
     *
     * @param string $license  License ID or code
     * @return array Response from PocketBase
     */
    public static function activated_license(string $license = ''): array{
        // Instanciar cliente PocketBase
        $pb = new self();
        // Validar entrada
        if(empty($license)){
            return [
                'success' => false,
                'message' => 'Se requiere código de licencia.'
            ];
        }
        // Endpoint base
        $endpoint = '/api/collections/ews_windows_activator/records/'.urlencode($license);
        // Obtener datos actuales de la licencia
        $license_data = $pb->request('GET', $endpoint);
        // Manejo de errores del request GET
        if(!isset($license_data['id'])){
            return [
                'success' => false,
                'message' => $license_data['message'] ?? 'Licencia no encontrada.'
            ];
        }
        // Extraer datos relevantes de la licencia
        $license_limit_activations = (int)($license_data['limit_activations'] ?? 0);
        $license_count_activations = (int)($license_data['count_activations'] ?? 0);
        $license_count_requests    = (int)($license_data['count_requests'] ?? 0);
        // Verificar si la licencia está activa
        if(!$license_data['active'] || $license_data['active'] == false){
            return [
                'success' => false,
                'message' => 'La licencia está bloqueada.'
            ];
        }
        // Calcular contador de solicitudes y activaciones
        $counter_requests = (int)$license_count_requests + 1;
        $counter_activations = (int)$license_count_activations + 1;
        // Verificar límite de activaciones
        if($license_limit_activations != 0 && $license_count_activations >= $license_limit_activations){
            // Actualizar solo el contador de solicitudes
            $pb->request('PATCH', $endpoint, ['count_requests' => $counter_requests]);
            // Notificar que se alcanzó el límite de activaciones
            return [
                'success' => false,
                'message' => 'Se alcanzó el límite de activaciones de licencias.'
            ];
        }
        // Preparar datos a actualizar
        $payload = [
            'activated'=> true,
            'ip_address' => get_ip_address() ?? ($_SERVER['REMOTE_ADDR'] ?? ''),
            'device_details' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'count_requests' => $counter_requests,
            'count_activations'  => $counter_activations
        ];
        // Ejecutar actualización
        $response = $pb->request('PATCH', $endpoint, $payload);
        // Manejo de errores de actualización
        if(isset($response['error']) || isset($response['code'])){
            return [
                'success' => false,
                'message' => $response['message'] ?? 'Error al actualizar la licencia.'
            ];
        }
        // Éxito total
        return [
            'success' => true,
            'message' => 'Licencia activada exitosamente.',
            'data' => $response
        ];
    }

    public static function send_email(string $to = '', string $subjet = '', string $message = ''): array{


    }
}
/*

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


echo json_encode(PocketBase::activated_license('bfd6696cd9833474673d57bc4a6a4513'));


echo json_encode(PocketBase::add_license([
    'email' => 'emeza@ews.pe',
    'phone' => '51933585544',
    'name' => 'Erick',
    'surname' => 'Meza',
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
    'device_details' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'product' => 'Office 2021',
    'currency' => 'PEN',
    'payment_method' => 'YAPE',
    'payment_description' => '',
    'total_expenditure' => 0,
    'total_payment' => 35,
    'count_requests' => 1,
    'count_activations' => 0,
    'limit_activations' => 10,
    'active' => true,
]));*/