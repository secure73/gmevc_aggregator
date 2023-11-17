<?php
namespace Aggregator\Helper;

class RedisResponseCache{
    private $redis;
    private $cacheKey;
    private $cacheTtl;

    public function __construct($redis, $cacheKey, $cacheTtl)
    {
        $this->redis = $redis;
        $this->cacheKey = $cacheKey;
        $this->cacheTtl = $cacheTtl;
    }

    public function getResponse()
    {
        $response = $this->redis->get($this->cacheKey);
        if ($response) {
            return $response;
        }
        return null;
    }

    public function setResponse($response)
    {
        $this->redis->set($this->cacheKey, $response);
        $this->redis->expire($this->cacheKey, $this->cacheTtl);
    }
}