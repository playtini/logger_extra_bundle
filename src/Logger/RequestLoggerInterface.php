<?php

namespace Playtini\LoggerExtraBundle\Logger;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestLoggerInterface
{
    public function logRequest(Request $request) : void;
    public function logResponse(Request $request, Response $response) : void;
}