<?php

/**
 * 
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */
namespace LaminasFileUpload\Form;

use Laminas\Form\Form;

class MyForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }
    public function init()
    {
        $this->add([
            'type' => 'fileupload',
            'name' => 'start_date',
            'attributes' => [
                'formUniqueId'      => 'photo_',
                'id'                => 'photoPathId',
                'storage'           => 'hybrid', // 'filesystem' or 'db' or 'hybrid'
                'showProgress'      => true,
                'multiple'          => true,
                'enableRemove'      => true,
                'uploadDir'         => 'data/UserData/',
                'icon'              => 'fas fa-upload',
                'successIcon'       => 'fas fa-edit',
                'errorIcon'         => 'fas fa-remove',
                'class'             => 'btn btn-primary',
                'uploadText'        => 'Upload Photo',
                'successText'       => 'Change Photo',
                'errorText'         => 'Try Again',
                'uploadingText'     => 'Uploading Photo...',
                'replacePrevious'   => false,
                'randomizeName'     => true,
                'showPreview'       => true,
                'validator' => [ 
                    'allowedExtensions' => 'jpg,png',
                    'allowedMime'       => 'image/jpeg,image/png',
                    'minSize'           => 10,
                    'maxSize'           => 50000*1024,
                    'image' => [
                        'minWidth'  => 0,
                        'minHeight' => 0,
                        'maxWidth'  => 12000,
                        'maxHeight' => 10000,
                    ],
                ],
                //'crop' => [
                //    'width'  => 200,
                //    'height' => 200,
                //],
                'preview'=>[
                    'width'  => 100,
                    'height' => 100,
                ],
                'callback'=>[
                    //first callback must be as follows others can be configured as user desires
                    //[
                    //    'object'    => 'object',
                    //    'function'  => 'name of function to call',
                    //    'parameter' => 'name(s) with full path of file(s) uploaded separated with comma '
                    //]
                ]
            ],
            'options' => [
                'label' => 'Abc',
            ],
        ]);
        
        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitButton',
                'class' => 'btn btn-primary'
            ],
        ]);
    }
}