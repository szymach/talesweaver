<?php

namespace AppBundle\Element;

use Symfony\Component\Form\FormFactoryInterface;

use AppBundle\Entity\Paragraph;
use AppBundle\Form\ParagraphType;

/**
 * @author Piotr Szymaszek
 */
class ParagraphElement extends AbstractElement
{
    public function getId()
    {
        return 'paragraph';
    }

    public function getClassName()
    {
        return Paragraph::class;
    }

    public function getEntity()
    {
        return new Paragraph();
    }

    public function getForm(FormFactoryInterface $factory, $data = null, $options = [])
    {
        return $factory->create(
            ParagraphType::class,
            $data ? $data : $this->getEntity(),
            $options
        );
    }
}
