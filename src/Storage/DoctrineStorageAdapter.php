<?php

/**
 * 
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */

namespace LaminasFileUpload\Storage;

use Doctrine\Persistence\ObjectManager;
use LaminasFileUpload\Entity\FileEntityInterface;
use LaminasFileUpload\Storage\StorageInterface;
use Laminas\Session\Container;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DoctrineStorageAdapter implements StorageInterface {

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager 
     */
    protected $objectManager;

    /**
     * @var FileUpload\Entity\FileEntityInterface 
     */
    protected $fileObject;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    public function __construct(ObjectManager $objectManager, FileEntityInterface $fileObject) {
        $this->objectManager = $objectManager;
        $this->fileObject = $fileObject;
        $this->repository = $this->objectManager->getRepository(get_class($fileObject));
    }

    public function remove($fileUuid, $filePath) {
        $obj = $this->fetchObjectFromUploadNameandFileId('',$fileUuid);
        if(is_object($obj)){
            $this->objectManager->remove($obj);
            $this->objectManager->flush();
        }
        return;
    }

    public function store($file, $attributes) {
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
        return $obj;
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
            unlink($path);
        }
        return $fileObj;
    }

    public function fetchAllFromUploadName($uploadName) {
        $ret = [];
        $sessionSuccess = new Container('FormUploadSuccessContainer');
        if($sessionSuccess->offsetExists($uploadName)){
            $ids = $sessionSuccess->$uploadName;
            foreach($ids as $fileName=>$fileUuid){
                $obj = $this->fetchObjectFromUploadNameandFileId($uploadName, $fileUuid);
                if($obj instanceof \LaminasFileUpload\Entity\FileEntityInterface){
                    $ret[] = $obj;
                }
            }
        }
        return $ret;
    }

}
