<?php 
namespace GemAggregator\Helper;

use Swoole\Http\Client;
use Swoole\Http\Request;
use Swoole\Http\Response;


class SwooleProxy {
    private $remoteUrl;
    public ?string $error;

    public function __construct(string $remoteUrl) {
        $this->remoteUrl = $remoteUrl;
    }

    /**
     * @param Request $swoole_request
     * @param Response $swoole_response
     */
    public function forwardRequest(Request $swoole_request, Response $swoole_response) {
        $client = new Client($this->remoteUrl);

        // Forward request headers
        foreach ($swoole_request->header as $key => $value) {
            $client->setHeader($key, $value);
        }

        // Forward swoole_request body
        $client->setData($swoole_request->rawContent());

        // Forward swoole_request method and path
        $client->setMethod($swoole_request->server['request_method']);
        $client->setPath($swoole_request->server['request_uri']);

        // Forward request
        $client->execute(function (Client $client) use ($swoole_response) {
            if ($client->statusCode >= 399) {
                $this->error = 'Error: ' . $client->statusCode;
                $swoole_response->status($client->statusCode);
                $swoole_response->end($this->error);
                return;
            }

            // Forward response headers
            foreach ($client->headers as $key => $value) {
                $swoole_response->header($key, $value);
            }

            // Forward response body
            $swoole_response->end($client->body);
        });
    }
}

// Usage example:
//$proxy = new Proxy('http://example.com/forwarded-endpoint');
//$proxy->forwardRequest();
