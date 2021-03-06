<?php
namespace LaminasFileUpload\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;

use LaminasFileUpload\Service\FileUploadService;

class DeleteController extends AbstractActionController
{
    
    protected $moduleOptions;
    protected $uploadName;
    protected $uploadService;

    public function __construct(FileUploadService $uploadService) {
        $this->uploadService = $uploadService;
    }
    
    public function removeUploadAction(){
        $vm = new ViewModel();
        $vm->setTerminal(true);
        $vm->setTemplate('file-upload/delete');
        
        $uploadName = $this->params()->fromRoute('uploadname');
        $fileId = $this->params()->fromRoute('filename');
        
        if (! $uploadName || !$fileId) {
            return $this->redirect()->toRoute('home');
        }
        
        $session = new Container('FormUploadFormContainer');
        $atributes = $session->offsetGet($uploadName);
        
        if($atributes['enableRemove']!==TRUE){
            return;
        }
        
        $validators = $atributes['validator'];
        $buttonId = $atributes['id']!==''?$atributes['id'].'__button':'upload__button';
        
        $vm->setVariable('buttonId', $buttonId);
        $vm->setVariable('buttonSuccessText',$atributes['successText']!==''?$atributes['successText']:$atributes['uploadText']);
        $vm->setVariable('buttonSuccessIcon', $atributes['successIcon']);
        $vm->setVariable('buttonErrorText', $atributes['errorText']!==''?$atributes['errorText']:$atributes['uploadText']);
        $vm->setVariable('buttonErrorIcon', $atributes['errorIcon']);

        $this->uploadService->removeFileFromUploadNameAndFileId($uploadName, $fileId);
        
        $fileObjects = $this->uploadService->getFileObjectListFromUploadName($uploadName);
        
        $filteredNames = [];
        foreach($fileObjects as $f){
            if(is_file($f->getName())){
                $filteredNames[] = $f->getName();
            }
            else{
                $filteredNames[] = $f->getId();
            }
        }
        
        $this->uploadService->callBack($atributes['callback'], implode(',', $filteredNames));
        
        $previewDivId = $uploadName.'previewDivId';
        $vm->setVariable('previewDiv', $previewDivId);
        $vm->setVariable('isImage', isset($validators['image']));
        $vm->setVariable('showPreview', $atributes['showPreview']);
        $vm->setVariable('previewDim', $atributes['preview']);
        $vm->setVariable('inputId', $uploadName.'_Id');
        $vm->setVariable('downloadDiv', $uploadName.'_downloadDiv');
        $vm->setVariable('uploadName', $uploadName); 
        $vm->setVariable('files', $fileObjects);
        $vm->setVariable('enableRemove', $atributes['enableRemove']); 
        return $vm;
    }
}

