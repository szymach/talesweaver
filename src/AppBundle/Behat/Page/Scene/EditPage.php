<?php

namespace AppBundle\Behat\Page\Scene;

use AppBundle\Behat\Page\AbstractPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

/**
 * @author Piotr Szymaszek
 */
class EditPage extends AbstractPage
{
    protected $path = '/en/crud/scene/{id}/edit';

    protected $elements = [
        'form' => 'form[name=scene]',
    ];

    protected function verifyPage()
    {
        if (!$this->hasElement('form')) {
            throw new UnexpectedPageException('Unable to verify page');
        }
    }
}
