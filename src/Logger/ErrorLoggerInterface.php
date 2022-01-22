<?php

namespace Playtini\LoggerExtraBundle\Logger;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

interface ErrorLoggerInterface
{
    public function logError(ExceptionEvent $event);
}