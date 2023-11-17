<?php 
namespace GemAggregator\Helper;

use Swoole\Http\Client;
use Swoole\Http\Request;
use Swoole\Http\Response;


class SwooleProxy {
    private $remoteUrl;

    public function __construct(string $remoteUrl) {
        $this->remoteUrl = $remoteUrl;
    }

    public function forwardRequest(Request $request, Response $response) {
        $client = new Client($this->remoteUrl);

        // Forward request headers
        foreach ($request->header as $key => $value) {
            $client->setHeader($key, $value);
        }

        // Forward request body
        $client->setData($request->rawContent());

        // Forward request method and path
        $client->setMethod($request->server['request_method']);
        $client->setPath($request->server['request_uri']);

        // Forward request
        $client->execute(function (Client $client) use ($response) {
            // Forward response headers
            foreach ($client->headers as $key => $value) {
                $response->header($key, $value);
            }

            // Forward response body
            $response->end($client->body);
        });
    }
}

// Usage example:
//$proxy = new Proxy('http://example.com/forwarded-endpoint');
//$proxy->forwardRequest();
