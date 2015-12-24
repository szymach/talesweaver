<?php

namespace AppBundle\Element\Interfaces;

use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Piotr Szymaszek
 */
interface ElementInterface
{
    public function getId();

    public function getEntity();

    /**
     * @param DataSourceFactoryInterface $factory
     * @return DataSourceInterface
     */
    public function getSource(DataSourceFactoryInterface $factory);

    /**
     * @param DataGridFactoryInterface $factory
     * @return DataGridInterface
     */
    public function getGrid(DataGridFactoryInterface $factory);

    /**
     * @param FormFactoryInterface $factory
     * @param mixed $data
     * @return FormInterface
     */
    public function getForm(FormFactoryInterface $factory, $data = null);

    public function getClassName();
}
