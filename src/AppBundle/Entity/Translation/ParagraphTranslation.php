<?php

namespace AppBundle\Entity\Translation;

use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

use AppBundle\Entity\Paragraph;
use AppBundle\Entity\Traits\LocaleTrait;

class ParagraphTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Paragraph
     */
    private $paragraph;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $text
     *
     * @return ParagraphTranslation
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
     * @param Paragraph $paragraph
     *
     * @return ParagraphTranslation
     */
    public function setParagraph(Paragraph $paragraph = null)
    {
        $this->paragraph = $paragraph;

        return $this;
    }

    /**
     * @return Paragraph
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }
}
