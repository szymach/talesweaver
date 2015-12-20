<?php

namespace AppBundle\Element;

use Symfony\Component\Form\FormFactoryInterface;

use AppBundle\Entity\Chapter;
use AppBundle\Form\ChapterType;

/**
 * @author Piotr Szymaszek
 */
class ChapterElement extends AbstractElement
{
    public function getId()
    {
        return 'chapter';
    }

    public function getClassName()
    {
        return Chapter::class;
    }

    public function getEntity()
    {
        return new Chapter();
    }

    public function getForm(FormFactoryInterface $factory, $data = null, $options = [])
    {
        return $factory->create(
            ChapterType::class,
            $data ? $data : $this->getEntity(),
            $options
        );
    }
}
