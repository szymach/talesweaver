<?php

namespace AppBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

use AppBundle\Element\Interfaces\ElementInterface;
use AppBundle\Element\Manager\Manager;

/**
 * @author Piotr Szymaszek
 */
class FormController extends AbstractController
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var DataGridFactoryInterface
     */
    protected $datagridFactory;

    /**
     * @var DataSourceFactoryInterface
     */
    protected $dataSourceFactory;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(
        EngineInterface $templating,
        ObjectManager $entityManager,
        Manager $elementManager,
        FormFactoryInterface $formFactory,
        DataGridFactoryInterface $datagridFactory,
        DataSourceFactoryInterface $dataSourceFactory,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->entityManager = $entityManager;
        $this->elementManager = $elementManager;
        $this->formFactory = $formFactory;
        $this->datagridFactory = $datagridFactory;
        $this->dataSourceFactory = $dataSourceFactory;
        $this->router = $router;
    }

    public function createAction(Request $request, $elementName)
    {
        $element = $this->getElement($elementName);
        $form = $this->getForm($element);
        $submitted = $this->handleForm($request, $form);
        if ($submitted) {
            return $this->redirectToEdit($elementName, $form->getData()->getId());
        }

        return $this->templating->renderResponse(
            'crud\form.html.twig',
            [
                'form' => $form->createView(),
                'element' => $elementName
            ]
        );
    }

    public function editAction(Request $request, $elementName, $id)
    {
        $element = $this->getElement($elementName);
        $entity = $this->find($element, $id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $form = $this->getForm($element, $entity);
        $submitted = $this->handleForm($request, $form);
        if ($submitted) {
            return $this->redirectToEdit($elementName, $entity->getId());
        }

        return $this->templating->renderResponse(
            'crud\form.html.twig',
            [
                'form' => $form->createView(),
                'element' => $elementName
            ]
        );
    }

    public function deleteAction($elementName, $id)
    {
        $element = $this->getElement($elementName);

        $result = $this->entityManager
            ->getRepository($element->getClassName())
            ->createQueryBuilder('e')
            ->delete($element->getClassName(), 'e')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;

        return new JsonResponse([$result]);
    }

    /**
     * @param ElementInterface $element
     * @param int $id
     * @return mixed
     */
    protected function find(ElementInterface $element, $id)
    {
        return $this->entityManager
            ->getRepository($element->getClassName())
            ->find($id)
        ;
    }

    /**
     * @param ElementInterface $element
     * @param mixed $data
     * @return FormInterface
     */
    protected function getForm(ElementInterface $element, $data = null)
    {
        $options = ['method' => 'PUT'];
        if (!$data) {
            $options['method'] = 'POST';
        }
        return $element->getForm($this->formFactory, $data, $options);
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @return RedirectResponse | boolean
     */
    protected function handleForm(Request $request, FormInterface $form)
    {
        $success = false;
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            if (null === $data->getId()) {
                $this->entityManager->persist($data);
            }
            $this->entityManager->flush();
            $success = true;
        }

        return $success;
    }

    /**
     * @param string $element
     * @param int $id
     * @return RedirectResponse
     */
    protected function redirectToEdit($element, $id)
    {
        return new RedirectResponse(
            $this->router->generate(
                'crud_edit',
                ['elementName' => $element, 'id' => $id]
            )
        );
    }
}
