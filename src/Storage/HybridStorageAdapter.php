<?php

/**
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */

namespace LaminasFileUpload\Storage;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class HybridStorageAdapter extends DoctrineStorageAdapter {
    public function remove($fileUuid, $filePath) {
        $obj = $this->fetchObjectFromUploadNameandFileId('',$fileUuid);
        if(is_object($obj)){
            $this->objectManager->remove($obj);
            $this->objectManager->flush();
            unlink($filePath);
        }
        return;
    }
    
    public function createFileObjectFromPath($path, UuidInterface $uuid = NULL) {
        $fileObj = NULL;
        if (is_file($path)) {
            $fileObj = clone $this->fileObject;
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
            $content = file_get_contents($path);

            $fileObj->setContent($content);
            $fileObj->setExtension($ext);
            $fileObj->setMime($mime);
            $fileObj->setName($path);
            $fileObj->setSize(filesize($path));
            $fileObj->setId($uuid!==NULL?$uuid:Uuid::fromString(Uuid::uuid4()));
        }
        return $fileObj;
    }
    
    public function store($file, $attributes) {
        $uuid = NULL;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        
        if(isset($attributes['newName'])&&trim($attributes['newName'])!=''){
            $newname = $dir.'/'.trim(basename($attributes['newName']));
        }
        elseif(isset($attributes['randomizeName'])&&$attributes['randomizeName']==TRUE){
            $uuid = \Ramsey\Uuid\Uuid::uuid4();
            $newname = $dir.'/'.$uuid.'.'.$ext;
        }
        else{
            $newname = preg_replace('/\s+/', '', $file);
        }
        
        rename($file, $newname);
        
        $fileObj = $this->createFileObjectFromPath($newname, $uuid);
        $fileObj->setContent(NULL);
        if($fileObj instanceof \LaminasFileUpload\Entity\FileEntityInterface){
            if($attributes['multiple']==FALSE && isset($attributes['newId']) && ($attributes['newId'] instanceof \Ramsey\Uuid\UuidInterface)){
                $fileObj->setId($attributes['newId']);
            }
            $this->objectManager->merge($fileObj);
            $this->objectManager->flush();
            $fileObj = $this->repository->findOneBy([ 'name' => $fileObj->getName() ]);
        }
        return $fileObj;
    }
    
    public function fetchObjectFromUploadNameandFileId($uploadName, $fileId) {
        $obj = $this->repository->findOneBy(['id' => $fileId]);

        if($obj instanceof \LaminasFileUpload\Entity\FileEntityInterface && is_file($obj->getName())){
            $obj->setContent(file_get_contents($obj->getName()));
        }
        return $obj;
    }
}

