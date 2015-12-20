<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Piotr Szymaszek
 */
class FormController extends AbstractController
{
    public function __construct(
        $templating,
        $entityManager,
        $elementManager,
        $formFactory,
        $datagridFactory,
        $dataSourceFactory,
        $routing
    ) {
        $this->templating = $templating;
        $this->entityManager = $entityManager;
        $this->elementManager = $elementManager;
        $this->formFactory = $formFactory;
        $this->datagridFactory = $datagridFactory;
        $this->dataSourceFactory = $dataSourceFactory;
        $this->routing = $routing;
    }

    public function createAction(Request $request, $elementName)
    {
        $element = $this->getElement($elementName);

        return $this->templating->renderResponse(
            'crud\form.html.twig',
            ['form' => $this->getForm($request, $element, null)]
        );
    }

    public function editAction(Request $request, $elementName, $id)
    {
        $element = $this->getElement($elementName);
        $entity = $this->find($element, $id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->templating->renderResponse(
            'crud\form.html.twig',
            ['form' => $this->getForm($request, $element, $entity)]
        );
    }

    public function deleteAction($elementName, $id)
    {
        $element = $this->getElement($elementName);

        $this->entityManager
            ->getRepository($element->getClassName())
            ->createQueryBuilder('e')
            ->delete($element->getClassName(), 'e')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;

        return new RedirectResponse(
            $this->routing->generate('list', ['elementName' => $element->getId()])
        );
    }

    protected function find($element, $id)
    {
        return $this->entityManager
            ->getRepository($element->getClassName())
            ->find($id)
        ;
    }

    protected function getForm($request, $element, $data)
    {
        $options = ['method' => 'PUT'];
        if (!$data) {
            $options['method'] = 'POST';
        }
        $form = $element->getForm($this->formFactory, $data, $options);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            if (null === $data->getId()) {
                $this->entityManager->persist($data);
            }
            $this->entityManager->flush();
        }

        return $form->createView();
    }
}
