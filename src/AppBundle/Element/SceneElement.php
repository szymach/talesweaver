<?php

namespace AppBundle\Element;

use Symfony\Component\Form\FormFactoryInterface;

use AppBundle\Entity\Scene;
use AppBundle\Form\SceneType;

/**
 * @author Piotr Szymaszek
 */
class SceneElement extends AbstractElement
{
    public function getId()
    {
        return 'scene';
    }

    public function getClassName()
    {
        return Scene::class;
    }

    public function getEntity()
    {
        return new Scene();
    }

    public function getForm(FormFactoryInterface $factory, $data = null, $options = [])
    {
        return $factory->create(
            SceneType::class,
            $data ? $data : $this->getEntity(),
            $options
        );
    }
}
