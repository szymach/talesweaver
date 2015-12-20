<?php

namespace AppBundle\Element;

use Symfony\Component\Form\FormFactoryInterface;

use AppBundle\Entity\Section;
use AppBundle\Form\SectionType;

/**
 * @author Piotr Szymaszek
 */
class SectionElement extends AbstractElement
{
    public function getId()
    {
        return 'section';
    }

    public function getClassName()
    {
        return Section::class;
    }

    public function getEntity()
    {
        return new Section();
    }

    public function getForm(FormFactoryInterface $factory, $data = null, $options = [])
    {
        return $factory->create(
            SectionType::class,
            $data ? $data : $this->getEntity(),
            $options
        );
    }
}
