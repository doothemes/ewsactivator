<?php
/**
 * HttpClient.php
 *
 * Lightweight HTTP client that avoids external proxies.
 * - Uses direct connections by default.
 * - Can bind to local interfaces (if the server has multiple public IPs).
 * - Optional Tor via local SOCKS5 (127.0.0.1:9050) if enabled.
 * - Rotates User-Agent and adds optional spoofed header(s).
 * - Retries with exponential backoff.
 *
 * Comments in English.
 *
 * Usage:
 *   $client = new HttpClient(['203.0.113.10','203.0.113.11'], useTor: false);
 *   $res = $client->request('https://example.com/', ['spoof_xff'=>true, 'retries'=>2]);
 */

class HttpClient
{
    /** @var array Local interfaces (IP strings) that can be used with CURLOPT_INTERFACE */
    private array $localInterfaces = [];

    /** @var bool Use Tor via local SOCKS5 (127.0.0.1:9050). Default false. */
    private bool $useTor = false;

    /** @var string Tor proxy address (default) */
    private string $torProxy = '127.0.0.1:9050';

    /** @var array User agents list */
    private array $userAgents = [];

    /** @var int Default connect timeout seconds */
    private int $connectTimeout = 8;

    /** @var int Default overall timeout seconds */
    private int $timeout = 25;

    /**
     * Constructor.
     *
     * @param array $localInterfaces Array of local IPs to bind to (optional).
     * @param bool $useTor If true, use local Tor SOCKS5 (127.0.0.1:9050).
     * @param string|null $torProxy Optional tor address "host:port".
     */
    public function __construct(array $localInterfaces = [], bool $useTor = false, ?string $torProxy = null)
    {
        $this->localInterfaces = array_values(array_filter($localInterfaces, 'filter_var'));
        $this->useTor = (bool)$useTor;
        if ($torProxy !== null) $this->torProxy = $torProxy;

        // Minimal realistic UAs; extend as needed
        $this->userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) Gecko/20100101 Firefox/123.0',
            'curl/8.5.0'
        ];
    }

    /**
     * Generate a random public IPv4 for X-Forwarded-For header only.
     * NOTE: This is header-only, it does NOT change the real source IP.
     *
     * @return string
     */
    private function randomPublicIp(): string
    {
        while (true) {
            $ip = mt_rand(1, 255) . '.' . mt_rand(0,255) . '.' . mt_rand(0,255) . '.' . mt_rand(1,254);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }

    /**
     * Pick a random local interface if available.
     *
     * @return string|null
     */
    private function pickLocalInterface(): ?string
    {
        if (empty($this->localInterfaces)) return null;
        return $this->localInterfaces[array_rand($this->localInterfaces)];
    }

    /**
     * Perform HTTP GET (or other method) with retries and optional binding/Tor.
     *
     * @param string $url
     * @param array $opts Supported options:
     *   - 'method' (string) default 'GET'
     *   - 'headers' (array) additional headers
     *   - 'spoof_xff' (bool) add random X-Forwarded-For header (default false)
     *   - 'retries' (int) number of retries on failure (default 1)
     *   - 'bind' (string|null) specific local interface to bind; overrides auto-pick
     *   - 'use_tor' (bool|null) override class-level tor usage for this request
     *
     * @return array ['success'=>bool,'http_code'=>int,'error'=>?string,'body'=>?string,'user_agent'=>string,'used_interface'=>?string]
     */
    public function request(string $url, array $opts = []): array
    {
        $method = strtoupper($opts['method'] ?? 'GET');
        $retries = max(0, (int)($opts['retries'] ?? 1));
        $spoofXff = !empty($opts['spoof_xff']);
        $providedBind = !empty($opts['bind']) ? $opts['bind'] : null;
        $useTor = isset($opts['use_tor']) ? (bool)$opts['use_tor'] : $this->useTor;
        $additionalHeaders = is_array($opts['headers'] ?? null) ? $opts['headers'] : [];

        $attempt = 0;
        $lastError = null;
        $ua = $this->userAgents[array_rand($this->userAgents)];
        $usedInterface = null;

        // exponential backoff base (in microseconds)
        $backoffBase = 200000; // 200ms

        do {
            $attempt++;
            $ch = curl_init();

            // Setup URL & method
            if ($method === 'GET' || $method === 'HEAD') {
                curl_setopt($ch, CURLOPT_URL, $url);
            } else {
                // For other methods, expect opts['body'] string or array
                $body = $opts['body'] ?? '';
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                if ($body !== '') {
                    if (is_array($body)) $body = http_build_query($body);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                }
            }

            // Core options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_HEADER, false);

            // Rotate UA per attempt
            $ua = $this->userAgents[array_rand($this->userAgents)];
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);

            // Headers
            $headers = [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9',
                'Connection: keep-alive',
            ];

            // spoofed X-Forwarded-For header (optional)
            if ($spoofXff) {
                $headers[] = 'X-Forwarded-For: ' . $this->randomPublicIp();
            }

            // merge additional headers (preserve keys if provided as associative)
            foreach ($additionalHeaders as $h) {
                $headers[] = $h;
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Determine binding interface
            $bindIf = $providedBind ?? $this->pickLocalInterface();
            if ($bindIf) {
                // Use CURLOPT_INTERFACE - requires that the IP is actually assigned to the server.
                curl_setopt($ch, CURLOPT_INTERFACE, $bindIf);
                $usedInterface = $bindIf;
            } else {
                $usedInterface = null;
            }

            // Optionally use Tor (local SOCKS5) if requested and available
            if ($useTor) {
                // Use SOCKS5 hostname resolution to avoid DNS leaks
                curl_setopt($ch, CURLOPT_PROXY, $this->torProxy);
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);
            }

            // Execute
            $body = curl_exec($ch);
            $err = curl_error($ch);
            $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Success conditions: no curl error and HTTP < 400
            if ($err === '' && $httpCode > 0 && $httpCode < 400 && $body !== false) {
                return [
                    'success' => true,
                    'http_code' => $httpCode,
                    'error' => null,
                    'body' => $body,
                    'user_agent' => $ua,
                    'used_interface' => $usedInterface,
                    'attempts' => $attempt,
                ];
            }

            // Record last error
            $lastError = $err ?: "HTTP {$httpCode}";

            // Backoff before next attempt
            if ($attempt <= $retries) {
                // exponential backoff with jitter
                $sleep = (int)($backoffBase * (2 ** ($attempt - 1)));
                $jitter = (int)($sleep * 0.2 * (mt_rand(-100, 100) / 100)); // +/-20% jitter
                $sleep = max(100000, $sleep + $jitter); // at least 100ms
                usleep($sleep);
            }

        } while ($attempt <= $retries);

        // If we reach here, all attempts failed
        return [
            'success' => false,
            'http_code' => $httpCode ?? 0,
            'error' => "Connection failed after {$attempt} attempts: {$lastError}",
            'body' => null,
            'user_agent' => $ua,
            'used_interface' => $usedInterface,
            'attempts' => $attempt,
        ];
    }
}