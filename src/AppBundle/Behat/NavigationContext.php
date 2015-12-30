<?php

namespace AppBundle\Behat;

use Exception;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class NavigationContext extends DefaultContext
{
    /**
     * @Transform :page
     */
    public function castPageNameToPageObject($page)
    {
        switch ($page) {
            case 'listing scenes':
                return $this->getPage('Scene\ListPage');
            case 'creating scenes':
                return $this->getPage('Scene\CreatePage');
            case 'editing scenes':
                return $this->getPage('Scene\EditPage');
            default:
                throw new Exception(sprintf('Cant cast "%s" to page object', $page));
        }
    }

    /**
     * @Given I am on the page for :page
     * @Given I should be on the page for :page
     */
    public function iAmOnPage(Page $page)
    {
        $page->open();
    }
}
