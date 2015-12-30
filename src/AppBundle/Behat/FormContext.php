<?php

namespace AppBundle\Behat;

use AppBundle\Behat\Element\FormElement;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Exception;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class FormContext extends DefaultContext
{
    /**
     * @When field :field should have the following selectable options
     */
    public function fieldShouldHaveFollowingSelectableOptions($field, TableNode $optionsTable)
    {
        $fieldNode = $this->getFormElement()->findField($field);
        if (!$fieldNode) {
            throw new Exception(sprintf('Unable to find field %s', $field));
        }
        $currentOptions = array_map(function (NodeElement $node) {
            return $node->getText();
        }, $fieldNode->findAll('css', 'option'));
        expect($currentOptions)->toContain('');
        $options = $optionsTable->getHash();
        $expectedOptionsCount = count($options);
        foreach ($options as $option) {
            expect($currentOptions)->toContain($option['Option name']);
        }
        expect(count($currentOptions) - 1)->toBe($expectedOptionsCount);
    }

    /**
     * @When field :field should only have the following selectable options available
     */
    public function fieldShouldOnlyHaveFollowingSelectableOptionsAvailable($field, TableNode $options)
    {
        $currentOptions = array_map(function (NodeElement $node) {
            return $node->getText();
        }, $this->getElement('FormElement')->findField($field)->findAll('css', 'option'));
        foreach ($options as $option) {
            expect($currentOptions)->toContain($option['Option name']);
        }
        expect(count($options->getHash()))->toBe(count($currentOptions));
    }

    /**
     * @Then I should see the button :label
     * @Then button :label
     */
    public function iShouldSeeTheButton($label)
    {
        $hasFormButton = $this->getFormElement()->hasButton($label);
        $hasLink = $this->getContentElement()->hasLink($label);
        expect($hasFormButton || $hasLink)->toBe(true);
    }

    /**
     * @When I click the button :name
     * @When click the button :name
     */
    public function iClickTheButton($name)
    {
        if ($this->getFormElement()->hasButton($name)) {
            $this->getFormElement()->pressButton($name);
        } elseif ($this->getContentElement()->hasLink($name)) {
            $this->getContentElement()->clickLink($name);
        } elseif ($this->getContentElement()->hasButton($name)) {
            $this->getContentElement()->pressButton($name);
        } else {
            throw new UnexpectedPageException(sprintf('Can\'t find button or link "%s"', $name));
        }
    }

    /**
     * @Then field :name should have the following error message:
     */
    public function fieldShouldHaveFollowingErrorMessage($name, PyStringNode $message)
    {
        expect($this->getFormElement()->getFieldErrors($name))->toBe($message->getRaw());
    }

    /**
     * @Given I type :value into :field
     */
    public function iTypeIntoField($field, $value)
    {
        $this->getFormElement()->fillField($field, $value);
    }

    /**
     * @Given I check the checkbox :field
     */
    public function iCheckCheckbox($field)
    {
        $this->getFormElement()->checkField($field);
    }

    /**
     * @Given I uncheck the checkbox :field
     */
    public function iUncheckCheckbox($field)
    {
        $this->getFormElement()->uncheckField($field);
    }

    /**
     * @return FormElement
     */
    private function getFormElement()
    {
        return $this->getElement('FormElement');
    }
}
