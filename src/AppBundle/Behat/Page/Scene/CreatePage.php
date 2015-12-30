<?php

namespace AppBundle\Behat\Page\Scene;

use AppBundle\Behat\Page\AbstractPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

/**
 * @author Piotr Szymaszek
 */
class CreatePage extends AbstractPage
{
    protected $path = '/en/crud/scene/create/{id}';

    protected $elements = [
        'form' => 'form[name=scene]',
    ];

    protected function verifyPage()
    {
        if (!$this->hasElement('list')) {
            throw new UnexpectedPageException('Unable to verify page');
        }
    }
}
