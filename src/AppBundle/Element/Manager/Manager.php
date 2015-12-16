<?php

namespace AppBundle\Element\Manager;

use Exception;

use AppBundle\Element\Interfaces\ElementInterface;

/**
 * @author Piotr Szymaszek
 */
class Manager
{
    private $elements = [];

    public function addElement(ElementInterface $element)
    {
        $id = $element->getId();
        if (!isset($this->elements[$id])) {
            $this->elements[$id] = $element;
        }
    }

    public function getElement($id)
    {
        if (!isset($this->elements[$id])) {
            throw new Exception(sprintf('Element with id %s does not exist!', $id));
        }

        return $this->elements[$id];
    }

    public function getElements()
    {
        return $this->elements;
    }
}
