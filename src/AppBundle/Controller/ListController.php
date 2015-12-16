<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ListController
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

    public function listAction(Request $request, $elementName)
    {
        $element = $this->getElement($elementName);

        return $this->templating->renderResponse(
            'crud\list.html.twig',
            ['datagrid' => $this->getData($request, $element)]
        );
    }

    private function getElement($name)
    {
        return $this->elementManager->getElement($name);
    }

    private function getData($request, $element)
    {
        $datasource = $element->getSource($this->dataSourceFactory);
        $datasource->bindParameters($request);

        $datagrid = $element->getGrid($this->datagridFactory);
        $datagrid->setData($datasource->getResult());

        return $datagrid->createView();
    }
}
