<?php
namespace LaminasFileUpload\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

use LaminasFileUpload\Service\FileUploadService;

class FileUploadServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null)
    {
        $moduleOptions  = $container->get(\LaminasFileUpload\ModuleOptions\ModuleOptions::class);
        $adapters       = [
            'filesystem' => $container->get(\LaminasFileUpload\Storage\FileSystemStorageAdapter::class),
            'db'         => $container->get(\LaminasFileUpload\Storage\DoctrineStorageAdapter::class),
        ];
        return new FileUploadService($adapters, $container, $moduleOptions);
    }
}

