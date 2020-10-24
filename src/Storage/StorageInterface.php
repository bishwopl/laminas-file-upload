<?php

/**
 * 
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */

namespace LaminasFileUpload\Storage;

interface StorageInterface {

    /**
     * Path of file
     * @param string $filePath
     * @param array $attributes
     */
    public function store($filePath, $attributes);

    /**
     * 
     * @param type $fileUuid
     * @param type $filePath
     */
    public function remove($fileUuid, $filePath);
    
     /**
     * @param type $pathOrId
     * @return \LaminasFileUpload\Entity\FileEntityInterface  | NULL
     */
    public function fetchObjectFromUploadNameandFileId($uploadName, $fileId);
    
    public function fetchAllFromUploadName($uploadName);

    public function createFileObjectFromPath($path);

}
