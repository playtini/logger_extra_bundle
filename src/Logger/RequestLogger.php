<?php

namespace Playtini\LoggerExtraBundle\Logger;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger implements RequestLoggerInterface
{
    private ParameterBagInterface $parameterBag;
    
    protected LoggerInterface $logger;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger)
    {
        $this->parameterBag = $parameterBag;
        $this->logger = $logger;
    }

    public function logRequest(Request $request): void
    {
        $msg = "{$this->parameterBag->get('service_name')}.request.{$request->getMethod()}";

        $params = [];
        if ($request->getMethod() === Request::METHOD_GET) {
            $params = $request->query->all();
        }
        if ($request->getMethod() === Request::METHOD_POST) {
            $params = $request->toArray();
        }
        
        $this->logger->info($msg, [
            'ip' => $request->getClientIp(),
            'url' => $request->getUri(),
            'request_method' => $request->getMethod(),
            'request_body_params' => $this->stringify($params),
        ]);
    }

    public function logResponse(Request $request, Response $response): void
    {
        $msg = "{$this->parameterBag->get('service_name')}.response.{$request->getMethod()}";
        
        $this->logger->info($msg, [
            'request_ip' => $request->getClientIp(),
            'request_url' => $request->getUri(),
            'response_status_code' => $response->getStatusCode(),
            'response_time' => $this->getResponseTime($request),
            'response_memory_usage' => $this->getMemoryUsage(),
        ]);
    }

    private function stringify($data): string
    {
        if (!is_string($data)) {
            try {
                $data = \json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 10);
            } catch (\Throwable $t) {
                $data = (string)$data;
            }
        }
        
        return substr($data, 0, 10000);
    }

    private function getMemoryUsage() : int
    {
        $memory = memory_get_peak_usage(true);
        
        return $memory > 1024 ? (int) ($memory / 1024) : 0;
    }

    private function getResponseTime(Request $request) : ?int
    {
        if (!$request->server) {
            
            return null;
        }

        $requestTime = $request->server->get('REQUEST_TIME_FLOAT', $request->server->get('REQUEST_TIME'));
        
        $reqMilliSecond = (int) ($requestTime * 1000);
        $resMilliSecond = (int) (microtime(true) * 1000);
        
        return $resMilliSecond - $reqMilliSecond;
    }
}