<?php

namespace AppBundle\Element;

use Symfony\Component\Form\FormFactoryInterface;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;

/**
 * @author Piotr Szymaszek
 */
class BookElement extends AbstractElement
{
    public function getId()
    {
        return 'book';
    }

    public function getClassName()
    {
        return Book::class;
    }

    public function getEntity()
    {
        return new Book();
    }

    public function getForm(FormFactoryInterface $factory, $data = null, $options = [])
    {
        return $factory->create(
            BookType::class,
            $data ? $data : $this->getEntity(),
            $options
        );
    }
}
