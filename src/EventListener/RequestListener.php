<?php

namespace Playtini\LoggerExtraBundle\EventListener;

use Playtini\LoggerExtraBundle\Logger\RequestLoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class RequestListener
{
    private RequestLoggerInterface $logger;

    public function __construct(RequestLoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function onRequest(RequestEvent $event): void
    {
        if (HttpKernel::MAIN_REQUEST !== $event->getRequestType()) {
            return;
        }

        $this->logger->logRequest($event->getRequest());
    }
    
    public function onResponse(ResponseEvent $event): void
    {
        if (HttpKernel::MAIN_REQUEST !== $event->getRequestType()) {
            return;
        }

        $this->logger->logResponse($event->getRequest(), $event->getResponse());
    }
}