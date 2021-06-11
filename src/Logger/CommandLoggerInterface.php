<?php

namespace Playtini\LoggerExtraBundle\Logger;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandLoggerInterface
{
    public function log(Command $command, InputInterface $input, string $message, \Throwable $exception = null) : void;
}