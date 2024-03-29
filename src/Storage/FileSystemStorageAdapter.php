<?php

/**
 * 
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */

namespace LaminasFileUpload\Storage;

use LaminasFileUpload\Entity\FileEntityInterface;
use Laminas\Session\Container;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class FileSystemStorageAdapter implements StorageInterface{
    
    protected $fileObject;

    public function __construct(FileEntityInterface $fileObject) {
        $this->fileObject = $fileObject;
    }

    public function remove($fileUuid, $filePath) {
        if(is_file($filePath)){
            unlink($filePath);
        }
    }

    public function store($filePath, $attributes) {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $dir = pathinfo($filePath, PATHINFO_DIRNAME);
        
        if(isset($attributes['newName'])&&trim($attributes['newName'])!=''){
            $newname = $dir.'/'.trim(basename($attributes['newName']));
        }
        elseif(isset($attributes['randomizeName'])&&$attributes['randomizeName']==TRUE){
            $uuid = \Ramsey\Uuid\Uuid::uuid4();
            $newname = $dir.'/'.$uuid.'.'.$ext;
        }
        else{
            $newname = preg_replace('/\s+/', '', $filePath);
        }

        rename($filePath, $newname);
        
        $fileObj = $this->createFileObjectFromPath($newname, $uuid);
        
        return $fileObj;
    }

    public function fetchObjectFromUploadNameandFileId($uploadName, $fileId){
        $fileObject = NULL;
        $sessionSuccess = new Container('FormUploadSuccessContainer');
        if($sessionSuccess->offsetExists($uploadName)){
            $files = $sessionSuccess->$uploadName;
        }
        foreach($files as $fileName=>$fileUuid){
            $fileUuidString = $fileUuid->toString();
            if($fileUuidString == $fileId){
                $fileObject = $this->createFileObjectFromPath($fileName);
                $fileObject->setId($fileUuid);
                break;
            }
        }
        return $fileObject;
    }
    
    public function createFileObjectFromPath($path, UuidInterface $uuid = NULL) {
        $fileObj = NULL;
        if (is_file($path)) {
            $uuid = $uuid!==NULL?$uuid:Uuid::fromString(Uuid::uuid4());
            $fileObj = clone $this->fileObject;
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
            $content = file_get_contents($path);

            $fileObj->setId($uuid);
            $fileObj->setContent($content);
            $fileObj->setExtension($ext);
            $fileObj->setMime($mime);
            $fileObj->setName($path);
            $fileObj->setSize(filesize($path));
        }
        return $fileObj;
    }

    public function fetchAllFromUploadName($uploadName) {
        $ret = [];
        $sessionSuccess = new Container('FormUploadSuccessContainer');
        if($sessionSuccess->offsetExists($uploadName)){
            $ids = $sessionSuccess->$uploadName;
            foreach($ids as $fileName=>$fileUuid){
                $obj = $this->createFileObjectFromPath($fileName);
                if($obj instanceof \LaminasFileUpload\Entity\FileEntityInterface){
                    $obj->setId($fileUuid);
                    $ret[] = $obj;
                }
            }
        }
        return $ret;
    }

}