<?php
namespace LaminasFileUpload\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

use LaminasFileUpload\Form\MyForm;

class TestFileUploadControllerFactory implements FactoryInterface
{   
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null)
    {
        $formManager       = $container->get('FormElementManager');
        $myform            = $formManager->get(MyForm::class);
        $requestedNameAbs = '\\'.$requestedName;
        return new $requestedNameAbs($myform);
    }
}

