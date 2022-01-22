<?php

namespace Playtini\LoggerExtraBundle\Logger;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ErrorLogger implements ErrorLoggerInterface
{
    private ParameterBagInterface $parameterBag;

    protected LoggerInterface $logger;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger)
    {
        $this->parameterBag = $parameterBag;
        $this->logger = $logger;
    }

    public function logError(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $msg = "{$this->parameterBag->get('service_name')}.error";

        $this->logger->error($msg, [
            'msg' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}