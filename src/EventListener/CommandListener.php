<?php

namespace Service\LoggerExtraBundle\EventListener;

use Service\LoggerExtraBundle\Logger\CommandLoggerInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class CommandListener
{
    protected CommandLoggerInterface $commandLogger;
    
    public function __construct(CommandLoggerInterface $commandLogger)
    {
        $this->commandLogger = $commandLogger;
    }

    public function onCommandResponse(ConsoleCommandEvent $event) : void
    {
        $this->commandLogger->log($event->getCommand(), $event->getInput(), 'command_start');
    }

    public function onTerminateResponse(ConsoleTerminateEvent $event) : void
    {
        $this->commandLogger->log($event->getCommand(), $event->getInput(), 'command_end');
    }
    
    public function onConsoleException(ConsoleErrorEvent $event): void
    {
        $this->commandLogger->log($event->getCommand(), $event->getInput(), 'console_error', $event->getError());
    }
}