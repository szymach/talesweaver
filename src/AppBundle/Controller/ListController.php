<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ListController extends AbstractController
{
    public function __construct(
        $templating,
        $entityManager,
        $elementManager,
        $datagridFactory,
        $dataSourceFactory
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

    protected function getData($request, $element)
    {
        $datasource = $element->getSource($this->dataSourceFactory);
        $datasource->bindParameters($request);

        $datagrid = $element->getGrid($this->datagridFactory);
        $datagrid->setData($datasource->getResult());

        return $datagrid->createView();
    }
}
