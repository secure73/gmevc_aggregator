<?php

namespace GemAggregator\Helper;
//Proxy for Apache Server
class ApacheProxy {
    private $targetUrl;
    public ?string $error;

    public function __construct($targetUrl) {
        $this->targetUrl = $targetUrl;
    }

    public function forwardRequest():false|string {
        // Get the request method (GET, POST, etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        // Get the request headers
        $headers = getallheaders();

        // Get the request body
        $body = file_get_contents('php://input');

        // Validate the target URL
        if (!filter_var($this->targetUrl, FILTER_VALIDATE_URL)) {
            http_response_code(400);
            $this->error = "Invalid target URL";
            return false;
        }

        // Create and configure the cURL session
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->targetUrl,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        // Execute the cURL session and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            http_response_code(500);
            $this->error = curl_error($ch);
            curl_close($ch);
            return false;
        }

        // Get the response headers
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $responseHeaders = curl_getinfo($ch);

        // Close the cURL session
        curl_close($ch);

        // Remove any existing headers
        header_remove();

        // Set the response code
        http_response_code($responseCode);

        // Forward the response headers to the client
        foreach ($responseHeaders as $header => $value) {
            if (strpos($header, 'HTTP/') === false) {
                header("$header: $value");
            }
        }

        // Forward the response body to the client
        return $response;
    }
}

// Usage example:
//$proxy = new Proxy('http://example.com/forwarded-endpoint');
//$proxy->forwardRequest();
