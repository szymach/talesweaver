<?php

namespace AppBundle\Behat\Page\Chapter;

use AppBundle\Behat\Page\AbstractPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

/**
 * @author Piotr Szymaszek
 */
class CreatePage extends AbstractPage
{
    protected $path = '/en/crud/chapter/create';

    protected $elements = [
        'form' => 'form[name=chapter]',
    ];

    protected function verifyPage()
    {
        if (!$this->hasElement('form')) {
            throw new UnexpectedPageException('Unable to verify page');
        }
    }
}
