<?php

namespace AppBundle\Behat\Element;

use Behat\Mink\Element\NodeElement;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

/**
 * @author Piotr Szymaszek
 */
class ListElement extends Element
{
    protected $selector = '.table-datagrid';

    /**
     * @param string $columnHeader
     * @return bool
     */
    public function hasColumn($columnHeader)
    {
        return $this->has('css', 'thead > tr > th:contains("' . $columnHeader . '")');
    }

    /**
     * @param string $columnHeader
     * @return int
     */
    private function getColumnPosition($columnHeader, $throwIfNotFound = true)
    {
        $result = null;
        $columns = $this->findAll('xpath', "//thead/tr/th");
        foreach ($columns as $index => $columnElement) {
            $title = $columnElement->find(
                'xpath',
                '/span[contains(text(),"' . $columnHeader . '")]'
            );
            if (isset($title)) {
                $result = $index + 1;
                break;
            }
        }
        if (null === $result && $throwIfNotFound) {
            throw new UnexpectedPageException(sprintf(
                'Column with title "%s" does not exist in DataGrid at page "%s".',
                $columnHeader,
                $this->getName()
            ));
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getElementsCount()
    {
        return count($this->getRows());
    }

    /**
     * @param int $row
     * @param string $action
     */
    public function clickAction($row, $action)
    {
        $actionsCell = $this->getCellByColumnName('Actions', $row);
        $actionsCell->clickLink($action);
    }

    /**
     * @param string $columnName
     * @param int $rowNum
     * @return NodeElement|null
     */
    private function getCellByColumnName($columnName, $rowNum)
    {
        $columnPosition = $this->getColumnPosition($columnName);
        $row = $this->getRow($rowNum);
        return $row->find('xpath', '//td[' . $columnPosition . ']');
    }

    /**
     * @return NodeElement[]
     */
    public function getRows()
    {
        return $this->findAll('css', 'tbody > tr');
    }

    /**
     * @param int $number
     * @return NodeElement
     */
    public function getRow($number)
    {
        $row = $this->find('xpath', '//tbody/tr[' . $number . ']');
        if (!isset($row)) {
            throw new UnexpectedPageException(sprintf('Row "%s" does not exist in DataGrid', $number));
        }
        return $row;
    }
}
