<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Item
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Collection
     */
    private $translations;

    /**
     * @var Collection
     */
    private $scenes;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param Scene $scene
     *
     * @return Item
     */
    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes[] = $scene;
        }
    }

    /**
     * @param Scene $scene
     */
    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
    }

    /**
     * @return Collection
     */
    public function getScenes()
    {
        return $this->scenes;
    }
}
