<?php

namespace Playtini\LoggerExtraBundle\EventListener;

use Playtini\LoggerExtraBundle\Logger\RequestLoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestListener
{
    private RequestLoggerInterface $logger;

    public function __construct(RequestLoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function onRequest(RequestEvent $event): void
    {
        $this->logger->logRequest($event->getRequest());
    }
    
    public function onResponse(ResponseEvent $event): void
    {
        $this->logger->logResponse($event->getRequest(), $event->getResponse());
    }
}