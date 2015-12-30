<?php

namespace AppBundle\Behat\Page\Scene;

use AppBundle\Behat\Page\AbstractPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

/**
 * @author Piotr Szymaszek
 */
class ListPage extends AbstractPage
{
    protected $path = '/en/crud/scene/list';

    protected $elements = [
        'list' => '.table-datagrid',
    ];

    protected function verifyPage()
    {
        if (!$this->hasElement('list')) {
            throw new UnexpectedPageException('Unable to verify page');
        }
    }
}
