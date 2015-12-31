<?php

namespace AppBundle\Behat\Page\Book;

use AppBundle\Behat\Page\AbstractPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

/**
 * @author Piotr Szymaszek
 */
class CreatePage extends AbstractPage
{
    protected $path = '/en/crud/book/create';

    protected $elements = [
        'form' => 'form[name=book]',
    ];

    protected function verifyPage()
    {
        if (!$this->hasElement('form')) {
            throw new UnexpectedPageException('Unable to verify page');
        }
    }
}
