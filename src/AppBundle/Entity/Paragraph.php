<?php

namespace AppBundle\Entity;

class Paragraph
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     */
    private $text;
    
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
}
