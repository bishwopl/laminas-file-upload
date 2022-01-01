<?php
namespace LaminasFileUpload\ModuleOptions\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

use LaminasFileUpload\ModuleOptions\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null)
    {
        $config = $container->get('Config');
        $optArr = isset($config['laminas_file_uploadmodule_options']) ? $config['laminas_file_uploadmodule_options'] : [];
        return new ModuleOptions($optArr);        
    }
}
