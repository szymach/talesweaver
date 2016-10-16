<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @author Piotr Szymaszek
 */
class Character
{
    use Traits\TranslatableTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $name;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $description;

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
     * @param string $name
     *
     * @return Character
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     *
     * @return Character
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param Scene $scene
     *
     * @return Character
     */
    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes[] = $scene;
        }

        return $this;
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
    public function getScene()
    {
        return $this->scenes;
    }
}