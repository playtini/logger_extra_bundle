<?php

namespace Playtini\LoggerExtraBundle\EventListener;

use Playtini\LoggerExtraBundle\Logger\ErrorLoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ErrorListener
{
    private ErrorLoggerInterface $logger;

    public function __construct(ErrorLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onError(ExceptionEvent $event): void
    {
        $this->logger->logError($event);
    }
}