<?php

namespace Playtini\LoggerExtraBundle\Processor;

class AdditionsProcessor
{
    private array $vars;

    public function __construct(array $vars = [])
    {
        $this->vars = $vars;
    }
    
    public function __invoke(array $record): array
    {
        foreach ($this->vars as $key => $value) {
            $record['extra'][$key] = $value;
        }
        
        return $record;
    }
}