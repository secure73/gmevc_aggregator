<?php
namespace Aggregator;

class ClientRequestDispatcher
{
    private $client;
    private $request;

    public function __construct()
    {
        $this->client = $client;
        $this->request = $request;
    }

    public function dispatch()
    {
        $this->client->send($this->request);
    }
}

?>