<?php

/**
 * 
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */

namespace LaminasFileUpload\Storage\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

use LaminasFileUpload\Storage\DoctrineStorageAdapter;

class DoctrineStorageAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null)
    {
        $moduleOptions     = $container->get(\LaminasFileUpload\ModuleOptions\ModuleOptions::class);
        
        $ret = false;
        $fileCalssName = '\\'.$moduleOptions->getEntity();
        $fileObject = new $fileCalssName();
            
        $objectManager = $container->get($moduleOptions->getObjectmanager());
        $ret = new DoctrineStorageAdapter($objectManager, $fileObject);
        
        if($ret==false){
            throw new \Exception("Cannot create storage adapter! Check your configuration.");
        }
        
        return $ret;
    }
}
