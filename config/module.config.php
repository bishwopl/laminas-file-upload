<?php

namespace LaminasFileUpload;

use Ramsey\Uuid\Doctrine\UuidType;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . '/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'types' => [
                    UuidType::NAME => UuidType::class,
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            \LaminasFileUpload\Controller\TestFileUploadController::class =>
            \LaminasFileUpload\Controller\Factory\TestFileUploadControllerFactory::class,
            \LaminasFileUpload\Controller\FileUploadController::class =>
            \LaminasFileUpload\Controller\Factory\FileUploadControllerFactory::class,
            \LaminasFileUpload\Controller\DownloadController::class =>
            \LaminasFileUpload\Controller\Factory\FileUploadControllerFactory::class,
            \LaminasFileUpload\Controller\UploadController::class =>
            \LaminasFileUpload\Controller\Factory\FileUploadControllerFactory::class,
            \LaminasFileUpload\Controller\DeleteController::class =>
            \LaminasFileUpload\Controller\Factory\FileUploadControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \LaminasFileUpload\ModuleOptions\ModuleOptions::class =>
            \LaminasFileUpload\ModuleOptions\Factory\ModuleOptionsFactory::class,
            \LaminasFileUpload\Service\FileUploadService::class =>
            \LaminasFileUpload\Service\Factory\FileUploadServiceFactory::class,
            \LaminasFileUpload\Storage\StorageInterface::class =>
            \LaminasFileUpload\Storage\Factory\StorageAdapterFactory::class,
            \LaminasFileUpload\Storage\FileSystemStorageAdapter::class =>
            \LaminasFileUpload\Storage\Factory\FileSystemStorageAdapterFactory::class,
            \LaminasFileUpload\Storage\DoctrineStorageAdapter::class =>
            \LaminasFileUpload\Storage\Factory\DoctrineStorageAdapterFactory::class,
            \LaminasFileUpload\Storage\HybridStorageAdapter::class =>
            \LaminasFileUpload\Storage\Factory\DoctrineStorageAdapterFactory::class,
        ],
    ],
    'form_elements' => [
        'aliases' => [
            'fileupload' => \LaminasFileUpload\Form\Element\FileUpload::class,
        ],
        'factories' => [
            \LaminasFileUpload\Form\Element\FileUpload::class => InvokableFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'FormElement' => \LaminasFileUpload\Form\View\Helper\FormElement::class,
            'formElement' => \LaminasFileUpload\Form\View\Helper\FormElement::class,
            'formelement' => \LaminasFileUpload\Form\View\Helper\FormElement::class,
        ],
        'factories' => [
            'formFileUpload' => \LaminasFileUpload\Form\View\Helper\Factory\FormFileUploadFactory::class
        ],
    ],
    'router' => [
        'routes' => [
            'fileUpload' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/fileupload',
                    'defaults' => [
                        'controller' => \LaminasFileUpload\Controller\FileUploadController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'test' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/test',
                            'defaults' => [
                                'controller' => \LaminasFileUpload\Controller\TestFileUploadController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'upload' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/upload',
                            'defaults' => [
                                'controller' => \LaminasFileUpload\Controller\UploadController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'getUpload' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/get-uploaded-file[/:uploadname][/:filename]',
                            'defaults' => [
                                'controller' => \LaminasFileUpload\Controller\DownloadController::class,
                                'action' => 'getUpload',
                            ],
                        ],
                    ],
                    'preview' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/preview[/:uploadname][/:filename]',
                            'defaults' => [
                                'controller' => \LaminasFileUpload\Controller\FileUploadController::class,
                                'action' => 'preview',
                            ],
                        ],
                    ],
                    'removeUpload' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/remove-uploaded-file[/:uploadname][/:filename]',
                            'defaults' => [
                                'controller' => \LaminasFileUpload\Controller\DeleteController::class,
                                'action' => 'removeUpload',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'circlical' => [
        'user' => [
            'guards' => [
                'fileupload' => [
                    'controllers' => [
                        Controller\FileUploadController::class => [
                            'default' => [],
                            'actions' => [
                                'index' => [],
                            ],
                        ],
                        Controller\TestFileUploadController::class => [
                            'default' => [],
                            'actions' => [
                                'index' => [],
                            ],
                        ],
                        Controller\DeleteController::class => [
                            'default' => [],
                            'actions' => [
                                'index' => [],
                            ],
                        ],
                        Controller\DownloadController::class => [
                            'default' => [],
                            'actions' => [
                                'index' => [],
                            ],
                        ],
                        Controller\UploadController::class => [
                            'default' => [],
                            'actions' => [
                                'index' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'file-upload' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'file-upload/delete' => __DIR__ . '/../view/laminas-file-upload/upload/index.phtml',
        ],
    ],
];
