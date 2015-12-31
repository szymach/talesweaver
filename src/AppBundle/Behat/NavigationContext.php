<?php

namespace AppBundle\Behat;

use Exception;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use Symfony\Component\PropertyAccess\PropertyAccessor;

use AppBundle\Entity\Translation\BookTranslation;
use AppBundle\Entity\Translation\ChapterTranslation;
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
            case 'listing chapters':
                return $this->getPage('Chapter\ListPage');
            case 'creating chapters':
                return $this->getPage('Chapter\CreatePage');
            case 'editing chapters':
                return $this->getPage('Chapter\EditPage');
            case 'listing books':
                return $this->getPage('Book\ListPage');
            case 'creating books':
                return $this->getPage('Book\CreatePage');
            case 'editing books':
                return $this->getPage('Book\EditPage');
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
     * @Given I am on the page for editing scene :scene
     * @Then I should be on the page for editing scene :scene
     */
    public function iShouldBeOnEditingScene($scene)
    {
        $page = $this->getPage('Scene\EditPage');
        $entity = $this->findByTitle(SceneTranslation::class, $scene, 'scene');
        $page->open(['id' => $entity->getId()]);
    }

    /**
     * @Then I should be on the page for editing scenes for the :position scene
     */
    public function iShouldBeOnEditingSceneForSceneAtPosition($position)
    {
        $page = $this->getPage('Scene\EditPage');
        $scene = $this->findByPosition(SceneTranslation::class, $position);
        $page->open(['id' => $scene->getId()]);
    }

    /**
     * @Given I am on the page for editing chapter :chapter
     * @Then I should be on the page for editing chapter :chapter
     */
    public function iShouldBeOnEditingChapter($chapter)
    {
        $page = $this->getPage('Chapter\EditPage');
        $entity = $this->findByTitle(ChapterTranslation::class, $chapter, 'chapter');
        $page->open(['id' => $entity->getId()]);
    }

    /**
     * @Then I should be on the page for editing chapters for the :position chapter
     */
    public function iShouldBeOnEditingSceneForChapterAtPosition($position)
    {

        $page = $this->getPage('Chapter\EditPage');
        $chapter = $this->findByPosition(ChapterTranslation::class, $position);
        $page->open(['id' => $chapter->getId()]);
    }

    /**
     * @Given I am on the page for editing book :book
     * @Then I should be on the page for editing book :book
     */
    public function iShouldBeOnEditingBook($book)
    {
        $page = $this->getPage('Book\EditPage');
        $entity = $this->findByTitle(BookTranslation::class, $book, 'book');
        $page->open(['id' => $entity->getId()]);
    }

    /**
     * @Then I should be on the page for editing books for the :position book
     */
    public function iShouldBeOnEditingSceneForBookAtPosition($position)
    {

        $page = $this->getPage('Book\EditPage');
        $book = $this->findByPosition(BookTranslation::class, $position);
        $page->open(['id' => $book->getId()]);
    }

    /**
     * @param string $class
     * @param string $title
     * @param string $property
     * @return mixed
     * @throws Exception
     */
    private function findByTitle($class, $title, $property)
    {
        $translation = $this->getEntityRepository($class)->findOneByTitle($title);
        if (!$translation) {
            throw new Exception(
                sprintf('No entity with title "%s" exists', $title)
            );
        }
        /* @var $accessor PropertyAccessor */
        $accessor = $this->getService('property_accessor');

        return $accessor->getValue($translation, $property);
    }

    /**
     * @param string $class
     * @param int $position
     * @return mixed
     * @throws Exception
     */
    private function findByPosition($class, $position)
    {
        $entities = $this->getEntityRepository($class)
            ->createQueryBuilder('e')
            ->setMaxResults($position)
            ->getQuery()
            ->getResult()
        ;
        if (!$entities || !isset($entities[$position - 1])) {
            throw new Exception(
                sprintf('No entity for row at position "%s" exists', $position)
            );
        }

        return $entities[$position - 1];
    }
}
