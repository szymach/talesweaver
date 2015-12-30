<?php

namespace AppBundle\Behat\Element;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

/**
 * @author Piotr Szymaszek
 */
class FormElement extends Element
{
    protected $selector = 'form';

    /**
     * @param string $locator
     * @param string $tabName
     * @return bool
     */
    public function hasField($locator, $tabName = null)
    {
        return (null !== $this->findField($locator, $tabName));
    }

    /**
     * @param string $locator
     * @param string $tabName
     * @return NodeElement|null
     */
    public function findField($locator, $tabName = null)
    {
        if (empty($tabName)) {
            $form = $this;
        } elseif ($this->hasLink($tabName)) {
            $form = $this->getTabContent($tabName);
        } else {
            $form = $this->getSectionContent($tabName);
        }
        $field = $form->find('named', [
            'field', $this->getSelectorsHandler()->xpathLiteral($locator),
        ]);
        if (!empty($field)) {
            return $field;
        }
        $label = $this->findLabel($locator, $tabName);
        if (!empty($label)) {
            return $form->find('css', sprintf('#%s', $label->getAttribute('for')));
        }
    }

    /**
     * @param string $locator
     * @param string $tabName
     * @return bool
     */
    public function hasButton($locator, $tabName = null)
    {
        return (null !== $this->findButton($locator, $tabName));
    }

    /**
     * @param string $locator
     * @param string $tabName
     * @return NodeElement|null
     */
    public function findButton($locator, $tabName = null)
    {
        if (empty($tabName)) {
            $form = $this;
        } elseif ($this->hasLink($tabName)) {
            $form = $this->getTabContent($tabName);
        } else {
            $form = $this->getSectionContent($tabName);
        }
        return $form->find('named', ['button', $locator]);
    }

    /**
     * @param $fieldName
     *
     * @return \Behat\Mink\Element\NodeElement[]
     * @throws \Exception
     */
    public function getFieldOptions($fieldName)
    {
        if (!$field = $this->findField($fieldName)) {
            throw new \Exception(sprintf('Unable to find "%s" field', $fieldName));
        }
        return array_filter($field->findAll('css', 'option:not([disabled])'), function (NodeElement $option) {
            return $option->getText() !== '';
        });
    }

    /**
     * @param string $locator
     * @param string $value
     * @param string $tabName
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillField($locator, $value, $tabName = null)
    {
        $field = $this->findField($locator, $tabName);
        if (null === $field) {
            throw $this->elementNotFound('form field', 'id|name|label|value', $locator);
        }
        $field->setValue($value);
    }

    public function getFieldErrors($fieldName, $tabName = null)
    {
        $field = $this->findField($fieldName, $tabName);
        if (!$field) {
            throw new \Exception(sprintf('Unable to find field "%s"', $fieldName));
        }
        $errors = $this->findAll('css', sprintf('ul[data-for="%s"] li', $field->getAttribute('id')));
        return implode(' ', array_map(function (NodeElement $item) {
            return $item->getText();
        }, $errors));
    }

    public function getErrorMessages()
    {
        $alerts = $this->findAll('css', '.alert-danger');
        $text = '';
        foreach ($alerts as $alert) {
            /** @var NodeElement $alert */
            $text .= $alert->getText();
        }
        return $text;
    }

    /**
     * @param string $fieldName
     * @param null $tabName
     * @return NodeElement|null
     */
    public function findLabel($fieldName, $tabName = null)
    {
        if (empty($tabName)) {
            $form = $this;
        } elseif ($this->hasLink($tabName)) {
            $form = $this->getTabContent($tabName);
        } else {
            $form = $this->getSectionContent($tabName);
        }
        return $form->find('xpath', sprintf('.//label[contains(normalize-space(string(.)), \'%s\')]', $fieldName));
    }
}
