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
            'environment' => $this->parameterBag->get('environment_name'),
            'service_name' => $this->parameterBag->get('service_name'),
            'service_version' => $this->parameterBag->get('service_version'),
        ];
        
        $record['extra'] = array_merge($record['extra'], $recordExtra);
        
        return $record;
    }
}