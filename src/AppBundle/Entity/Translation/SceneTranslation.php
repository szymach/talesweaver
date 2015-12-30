<?php

namespace AppBundle\Entity\Translation;

use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

use AppBundle\Entity\Scene;
use AppBundle\Entity\Traits\LocaleTrait;

class SceneTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return SceneTranslation
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $text
     *
     * @return SceneTranslation
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param Scene $scene
     *
     * @return SceneTranslation
     */
    public function setScene(Scene $scene)
    {
        $this->scene = $scene;

        return $this;
    }

    /**
     * @return Scene
     */
    public function getScene()
    {
        return $this->scene;
    }
}
