<?php

/**
 * 
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */
namespace LaminasFileUpload\Entity;

interface FileEntityInterface {
    
    public function setId(\Ramsey\Uuid\UuidInterface $uuid);
    
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     *
     * @return File
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return File
     */
    public function setExtension($extension);

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension();

    /**
     * Set mime
     *
     * @param string $mime
     *
     * @return File
     */
    public function setMime($mime);

    /**
     * Get mime
     *
     * @return string
     */
    public function getMime();

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return File
     */
    public function setSize($size);

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize();

    /**
     * Set content
     *
     * @param string $content
     *
     * @return File
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();
}
