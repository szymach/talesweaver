<?php

namespace AppBundle\Behat;

use AppBundle\Behat\Element\ListElement;
use Behat\Gherkin\Node\TableNode;
use Exception;

/**
 * @author Piotr Szymaszek
 */
class ListContext extends DefaultContext
{
    /**
     * @Transform :position
     */
    public function castPositionToInteger($position)
    {
        switch ($position) {
            case 'first':
                return 1;
            case 'second':
                return 2;
            default:
                throw new Exception(sprintf('Unknown position "%s"', $position));
        }
    }
    /**
     * @Transform :count
     */
    public function castCountToInteger($count)
    {
        switch ($count) {
            case 'added':
            case 'one':
                return 1;
            default:
                return (int) $count;
        }
    }

    /**
     * @Then the table should have the following columns:
     */
    public function tableShouldHaveFollowingColumns(TableNode $table)
    {
        foreach ($table->getHash() as $columnData) {
            if (!$this->getListElement()->hasColumn($columnData['Name'])) {
                throw new Exception(
                    sprintf('Unable to find column "%s"', $columnData['Name'])
                );
            }
        }
    }

    /**
     * @Then there should be a column :columnName
     */
    public function thereShouldBeColumn($columnName)
    {
        expect($this->getListElement()->hasColumn($columnName))->toBe(true);
    }

    /**
     * @When I click the :action button in the :position row
     */
    public function iClickButtonInRow($action, $position)
    {
        $this->getListElement()->clickAction($position, $action);
    }

    /**
     * @Then there should be no elements on the list
     */
    public function thereShouldBeNoElementsOnList()
    {
        expect($this->getListElement()->getElementsCount())->toBe(0);
    }

    /**
     * @Then there should be :count elements on the list
     */
    public function thereShouldBeCountElementsOnList($count)
    {
        expect($this->getListElement()->getElementsCount())->toBe(intval($count));
    }

    /**
     * @return ListElement
     */
    private function getListElement()
    {
        return $this->getElement('ListElement');
    }
}
