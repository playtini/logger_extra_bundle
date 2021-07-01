<?php

namespace Playtini\LoggerExtraBundle\Processor;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MainProcessor
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }
    
    public function __invoke(array $record): array
    {
        $recordExtra = [
            'environment' => $this->parameterBag->get('log_env') ?: $this->parameterBag->get('kernel.environment'),
            'service_name' => $this->parameterBag->get('service_name'),
        ];
        
        $record['extra'] = array_merge($record['extra'], $recordExtra);
        
        return $record;
    }
}