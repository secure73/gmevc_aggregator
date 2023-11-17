<?php
namespace GemAggregator\Helper;

use GemAggregator\Helper\TypeHelper;

class GemRequest
{
    public    string       $requestedUrl;
    public    ?string      $queryString;
    public    ?string      $error;
    public    ?string      $authorizationHeader;
    public    ?string      $remoteAddress;
    /**
     * @var array<mixed>
     */
    public    array        $files;
    /**
     * @var array<mixed>
     */
    public    array        $post;
    public    mixed        $get;
    public    string       $userMachine;
    public    ?string      $requestMethod;
    private   string       $id;
    private   string       $time;
    private   float        $start_exec;


    public function __construct()
    {
        $this->start_exec = microtime(true);
        $this->id = TypeHelper::guid();
        $this->time = TypeHelper::timeStamp();
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getTime(): string
    {
        return $this->time;
    }

    public function getStartExecutionTime(): float
    {
        return  $this->start_exec;
    }

   /**
    * @param array<string> $requieredPost
    */
    public function hasRequiredPost(array $requieredPost): bool
    {
        foreach ($requieredPost as $item) {
            if (!isset($this->post[$item])) {
                $this->error = "Missing required Post: $item";
                return false;
            }
        }
        return true;
    }
}
