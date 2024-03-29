<?php
namespace LaminasFileUpload\Form\View\Helper;

use LaminasFileUpload\Form\Element;
use Laminas\Form\View\Helper\FormElement as BaseFormElement;
use Laminas\Form\ElementInterface;
  
class FormElement extends BaseFormElement
{
    public function render(ElementInterface $element): string
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        if ($element instanceof Element\FileUpload) {
            $helper = $renderer->plugin('formFileUpload');
            return $helper($element);
        }

        return parent::render($element);
    }
}