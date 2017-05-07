<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Book;
use AppBundle\Pagination\Book\BookAggregate;
use Twig_Extension;
use Twig_SimpleFunction;

class BookExtension extends Twig_Extension
{
    /**
     * @var BookAggregate
     */
    private $pagination;

    public function __construct(BookAggregate $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('bookChapters', [$this, 'getBookChaptersFunction'])
        ];
    }

    public function getBookChaptersFunction(Book $book, $page)
    {
        return $this->pagination->getChaptersForBook($book, $page);
    }
}
