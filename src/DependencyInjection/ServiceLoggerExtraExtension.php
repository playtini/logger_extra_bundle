<?php

namespace Playtini\LoggerExtraBundle\DependencyInjection;

use Service\LoggerExtraBundle\EventListener\CommandListener;
use Service\LoggerExtraBundle\Processor\AdditionsProcessor;
use Service\LoggerExtraBundle\Processor\MainProcessor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Parser;

class ServiceLoggerExtraExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        foreach ($bundles as $name => $extension) {
            if ($name === 'MonologBundle') {
                $config = (new Parser())->parse(file_get_contents(__DIR__ . '/../config/monolog.yaml'));
                $container->prependExtensionConfig('monolog', $config['monolog']);
                
                break;
            }
        }
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../config'));
        $loader->load('service.yaml');
        
        $this->addProcessors($container, $config);
        $this->addCommandListener($container, $config);
    }

    protected function addProcessors(ContainerBuilder $container, array $config): void
    {
        $definition = $container->getDefinition(MainProcessor::class);
        $definition->addTag('monolog.processor');
        
        $definition = $container->getDefinition(AdditionsProcessor::class);
        $definition
            ->addTag('monolog.processor')
            ->addArgument($config['processor']['additions']);
    }

    protected function addCommandListener(ContainerBuilder $container, array $config): void
    {
        if (!$config['logger']['on_command']) {
            $container->removeDefinition(CommandListener::class);
            
            return;
        }
   
        $container->getDefinition(CommandListener::class)
            ->addTag('kernel.event_listener', ['event' => ConsoleEvents::COMMAND, 'method' => 'onCommandResponse'])
            ->addTag('kernel.event_listener', ['event' => ConsoleEvents::TERMINATE, 'method' => 'onTerminateResponse'])
            ->addTag('kernel.event_listener', ['event' => ConsoleEvents::ERROR, 'method' => 'onConsoleException'])
        ;
    }
}