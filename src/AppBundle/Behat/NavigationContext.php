<?php

namespace AppBundle\Behat;

use Exception;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

use AppBundle\Entity\Translation\SceneTranslation;

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
     * @Then I should be on the page for :page
     */
    public function iAmOnPage(Page $page)
    {
        $page->open();
    }

    /**
     * @Then I should be on the page for editing scene :scene
     * @Then I am on the page for editing scene :scene
     */
    public function iShouldBeOnEditingScene($scene)
    {
        $page = $this->getPage('Scene\EditPage');
        $translation = $this->getEntityRepository(SceneTranslation::class)
            ->findOneByTitle($scene)
        ;
        if (!$translation) {
            throw new Exception(sprintf('No scene with title "%s" exists', $scene));
        }
        $page->open(['id' => $translation->getScene()->getId()]);
    }

    /**
     * @Then I should be on the page for editing scenes for the :position scene
     */
    public function iShouldBeOnEditingSceneForSceneAtPosition($position)
    {

        $page = $this->getPage('Scene\EditPage');
        $scenes = $this->getEntityRepository(SceneTranslation::class)
            ->createQueryBuilder('s')
            ->setMaxResults($position)
            ->getQuery()
            ->getResult()
        ;
        if (!$scenes || !isset($scenes[$position - 1])) {
            throw new Exception(sprintf('No scene for row at position "%s" exists', $position));
        }
        $scene = $scenes[$position - 1];
        $page->open(['id' => $scene->getId()]);
    }
}
