<?php

/**
 * 
 * @author Bishwo Prasad Lamichhane <bishwo.prasad@gmail.com>
 */
namespace LaminasFileUpload\Form\Element;

use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\InputFilter\InputProviderInterface;
use Laminas\Validator\Regex as RegexValidator;

class FileUpload extends Element implements InputProviderInterface
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
    * Get a validator if none has been set.
    *
    * @return ValidatorInterface
    */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Sets the validator to use for this element
     *
     * @param  ValidatorInterface $validator
     * @return self
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Provide default input rules for this element
     *
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [

        ];
    }
}