<?php
namespace LaminasFileUpload\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use LaminasFileUpload\Service\FileUploadService;

class FileUploadController extends AbstractActionController
{
    
    protected $moduleOptions;
    protected $uploadName;
    protected $uploadService;

    public function __construct(FileUploadService $uploadService) {
        $this->uploadService = $uploadService;
    }

    public function indexAction(){
        return new ViewModel();
    }
}

