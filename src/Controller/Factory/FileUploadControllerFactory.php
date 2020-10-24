<?php
namespace LaminasFileUpload\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class FileUploadControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null)
    {
        $uploadService = $container->get(\LaminasFileUpload\Service\FileUploadService::class);
        $requestedNameAbs = '\\'.$requestedName;
        return new $requestedNameAbs($uploadService);
    }
}
