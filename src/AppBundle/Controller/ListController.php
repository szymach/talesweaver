<?php

namespace AppBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

use AppBundle\Element\Interfaces\ElementInterface;
use AppBundle\Element\Manager\Manager;

class ListController extends AbstractController
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var Manager
     */
    protected $elementManager;

    /**
     * @var DataGridFactoryInterface
     */
    protected $datagridFactory;

    /**
     * @var DataSourceFactoryInterface
     */
    protected $dataSourceFactory;

    public function __construct(
        EngineInterface $templating,
        ObjectManager $entityManager,
        Manager $elementManager,
        DataGridFactoryInterface $datagridFactory,
        DataSourceFactoryInterface $dataSourceFactory
    ) {
        $this->templating = $templating;
        $this->entityManager = $entityManager;
        $this->elementManager = $elementManager;
        $this->datagridFactory = $datagridFactory;
        $this->dataSourceFactory = $dataSourceFactory;
    }

    public function indexAction()
    {
        return $this->templating->renderResponse('base.html.twig');
    }

    public function listAction(Request $request, $elementName)
    {
        $element = $this->getElement($elementName);

        return $this->templating->renderResponse(
            'crud\list.html.twig',
            [
                'datagrid' => $this->getData($request, $element),
                'element' => $elementName
            ]
        );
    }

    /**
     * @param Request $request
     * @param ElementInterface $element
     * @return DataGridView
     */
    protected function getData(Request $request, ElementInterface $element)
    {
        $datasource = $element->getSource($this->dataSourceFactory);
        $datasource->bindParameters($request);

        $datagrid = $element->getGrid($this->datagridFactory);
        $datagrid->setData($datasource->getResult());

        return $datagrid->createView();
    }
}
