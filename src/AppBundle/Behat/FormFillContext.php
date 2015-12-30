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
