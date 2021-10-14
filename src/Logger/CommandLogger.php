<?php

namespace Playtini\LoggerExtraBundle\Logger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class CommandLogger implements CommandLoggerInterface
{
    protected LoggerInterface $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function log(Command $command, InputInterface $input, string $message, \Throwable $exception = null) : void
    {
        $context = $this->createContext($command, $input);
        if (!$exception) {
            $this->logger->debug($message, $context);
            
            return;
        }
        
        $exceptionContext = array_merge($context, [
            'trace_string' => $exception->getTraceAsString(),
            'exception_class' => get_class($exception),
            'exception_message' => $exception->getMessage(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
        ]);

        try {
            $this->logger->error($message, $exceptionContext);
        } catch (\Throwable $t) {
            $this->logger->error($message, array_merge($context, ['exception_message' => $t->getMessage()]));
        }
    }

    public function createContext(Command $command, InputInterface $input) : array
    {
        $args = $input->getArguments();
        unset($args['command']);
        
        return [
            'command_name' => $command->getName(),
            'command_arguments' => $args,
        ];
    }
}