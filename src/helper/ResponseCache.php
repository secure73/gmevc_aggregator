<?php
namespace Aggregator\Helper;
use Predis\Client as Redis;
class ResponseRedisCache
{
    private $redis;
    private $liveCache;

    public function __construct()
    {
        // Initialize Redis connection
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);

        // Initialize live cache
        $this->liveCache = [];
    }

    public function getResponse($request)
    {
        // Generate a unique cache key based on the user request
        $cacheKey = $this->generateCacheKey($request);

        // Check if the response is already cached in Redis
        if ($this->redis->exists($cacheKey)) {
            // Return the cached response from Redis
            return $this->redis->get($cacheKey);
        }

        // Check if the response is already cached in live cache
        if (isset($this->liveCache[$cacheKey])) {
            // Return the cached response from live cache
            return $this->liveCache[$cacheKey];
        }

        // If the response is not cached, fetch it from the remote server
        $response = $this->fetchResponseFromServer($request);

        // Cache the response in Redis for future use
        $this->redis->set($cacheKey, $response);

        // Cache the response in live cache for faster access
        $this->liveCache[$cacheKey] = $response;

        // Return the fetched response
        return $response;
    }

    private function generateCacheKey($request)
    {
        // Generate a unique cache key based on the user request
        return md5(json_encode($request));
    }

    private function fetchResponseFromServer($request)
    {
        // Fetch the response from the remote server based on the user request
        // You can implement your own logic here to fetch the response
        // For example, you can use cURL or any other HTTP client library

        // For demonstration purposes, let's assume we are fetching a JSON response
        $url = 'https://example.com/api';
        $response = file_get_contents($url);

        return $response;
    }
}

// Usage example:
// $cache = new ResponseRedisCache();
// $response = $cache->getResponse($request);
// echo $response;