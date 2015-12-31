<?php

namespace AppBundle\Behat;

/**
 * @author Piotr Szymaszek
 */
class FormFillContext extends DefaultContext
{
    /**
     * @Given I fill out the form for a new scene :scene
     */
    public function iFillOutTheFormForANewScene($scene)
    {
        $this->getFormElement()->fillField('Title', $scene);
        $this->getFormElement()->fillField(
            'Content',
            'A paragraph to test things out. No big deal.'
        );
    }

    /**
     * @Given I modify the scene form
     */
    public function iModifySceneForm()
    {
        $this->getFormElement()->fillField('Title', 'Modified scene');
        $this->getFormElement()->fillField(
            'Content',
            'Changed content.'
        );
    }

    /**
     * @Given I fill out the form for a new chapter :chapter
     */
    public function iFillOutTheFormForANewChapter($chapter)
    {
        $this->getFormElement()->fillField('Title', $chapter);
    }

    /**
     * @Given I modify the chapter form
     */
    public function iModifyChapterForm()
    {
        $this->getFormElement()->fillField('Title', 'Modified chapter');
    }

    /**
     * @Given I fill out the form for a new book :book
     */
    public function iFillOutTheFormForANewBook($book)
    {
        $this->getFormElement()->fillField('Title', $book);
    }

    /**
     * @Given I modify the book form
     */
    public function iModifyBookForm()
    {
        $this->getFormElement()->fillField('Title', 'Modified book');
    }

    /**
     * @return FormElement
     */
    private function getFormElement()
    {
        return $this->getElement('FormElement');
    }

    /**
     * @return ContentElement
     */
    private function getContentElement()
    {
        return $this->getElement('ContentElement');
    }
}
