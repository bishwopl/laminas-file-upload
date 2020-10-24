<?php
namespace LaminasFileUpload\Form\View\Helper\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

use LaminasFileUpload\Form\View\Helper\FormFileUpload;

class FormFileUploadFactory implements FactoryInterface
{   
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null)
    {
        $uploadService = $container->get(\LaminasFileUpload\Service\FileUploadService::class);
        return new FormFileUpload($uploadService);
    }
}