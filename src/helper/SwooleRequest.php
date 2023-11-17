<?php
namespace Aggregator\Helper;

use Aggregator\Helper\StringHelper;
use Aggregator\Helper\GemRequest;

class SwooleRequest
{
    public   GemRequest $request; 
    private  object  $incommingRequestObject;
       
    /**
     * @param object $incommingRequestObject
     */
    public function __construct(object $swooleRquest)
    {
        $this->request = new GemRequest();
        $this->incommingRequestObject = $swooleRquest;
        if(isset($swooleRquest->server['request_uri']))
        {
            $this->request->requestMethod = $swooleRquest->server['request_method'];
            $this->request->requestedUrl = $swooleRquest->server['request_uri'];
            isset($swooleRquest->server['query_string']) ? $this->request->queryString = $swooleRquest->server['query_string'] : $this->request->queryString = null;
            $this->request->remoteAddress = $swooleRquest->server['remote_addr'] .':'. $swooleRquest->server['remote_port'];
            $this->request->userMachine = StringHelper::sanitizedString($swooleRquest->header['user-agent']);
            $this->setData();
        }
        else
        {
            $this->request->error = "incomming request is not openSwoole request";
        }
    }

    public function getOriginalSwooleRequest():object
    {
        return $this->incommingRequestObject;
    }

    private function setData()
    {
        $this->setAuthorizationToken();
        $this->setPost();
        $this->setFiles();
        $this->setGet();
    }


    private function setPost()
    {
        if(isset($this->incommingRequestObject->post))
        {
            $this->request->post = $this->incommingRequestObject->post;
        }
    }


    private function setAuthorizationToken():void
    {
        if(isset($this->incommingRequestObject->header['authorization']))
        {
            $this->request->authorizationHeader = $this->incommingRequestObject->header['authorization'];
        }
    }

    private function setFiles():void
    {
        if(isset($this->incommingRequestObject->files))
        {
            $this->request->files = $this->incommingRequestObject->files;
        }
    }

    private function setGet():void
    {
        if(isset($this->incommingRequestObject->get))
        {
            $this->request->get = $this->incommingRequestObject->get;
        }
    }
}