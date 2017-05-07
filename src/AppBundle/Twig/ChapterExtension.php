<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\ChapterAggregate;
use Twig_Extension;
use Twig_SimpleFunction;

class ChapterExtension extends Twig_Extension
{
    /**
     * @var ChapterAggregate
     */
    private $pagination;

    public function __construct(ChapterAggregate $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('chapterScenes', [$this, 'getChapterScenesFunction'])
        ];
    }

    public function getChapterScenesFunction(Chapter $chapter, $page)
    {
        return $this->pagination->getScenesForChapter($chapter, $page);
    }
}
