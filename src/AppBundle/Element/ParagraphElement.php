<?php

namespace AppBundle\Element;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

use AppBundle\Entity\Paragraph;
use AppBundle\Form\ParagraphType;

/**
 * @author Piotr Szymaszek
 */
class ParagraphElement implements Interfaces\ElementInterface
{
    public function getId()
    {
        return 'paragraph';
    }

    public function getClassName()
    {
        return Paragraph::class;
    }

    public function getEntity()
    {
        return new Paragraph();
    }

    public function getForm(FormFactoryInterface $factory, $data = null, $options = [])
    {
        return $factory->create(
            ParagraphType::class,
            $data ? $data : $this->getEntity(),
            $options
        );
    }

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

    private function getSourceParameters()
    {
        return ['entity' => $this->getClassName()];
    }
}
