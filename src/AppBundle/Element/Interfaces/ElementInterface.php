<?php

namespace AppBundle\Element\Interfaces;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Piotr Szymaszek
 */
interface ElementInterface
{
    public function getId();
    
    public function getEntity();
    
    public function getSource(DataSourceFactoryInterface $factory);
    
    public function getGrid(DataGridFactoryInterface $factory);
    
    public function getForm(FormFactoryInterface $factory, $data = null);
    
    public function getClassName();
}
