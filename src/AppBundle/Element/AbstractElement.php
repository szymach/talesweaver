<?php

namespace AppBundle\Element;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

/**
 * @author Piotr Szymaszek
 */
abstract class AbstractElement implements Interfaces\ElementInterface
{
    public function getGrid(DataGridFactoryInterface $factory)
    {
        return $factory->createDataGrid($this->getId());
    }

    public function getSource(DataSourceFactoryInterface $factory)
    {
        return $factory->createDataSource(
            'doctrine',
            $this->getSourceParameters(),
            $this->getId()
        )
        ->setMaxResults(10);
    }

    protected function getSourceParameters()
    {
        return ['entity' => $this->getClassName()];
    }
}
