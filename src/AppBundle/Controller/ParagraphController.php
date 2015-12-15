<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Paragraph;
use AppBundle\Form\ParagraphType;

/**
 * Paragraph controller.
 *
 */
class ParagraphController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $paragraphs = $em->getRepository(Paragraph::class)->findAll();

        return $this->render('paragraph/index.html.twig', array(
            'paragraphs' => $paragraphs,
        ));
    }

    public function newAction(Request $request)
    {
        $paragraph = new Paragraph();
        $form = $this->createForm(ParagraphType::class, $paragraph);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paragraph);
            $em->flush();

            return $this->redirectToRoute('paragraph_show', array('id' => $paragraph->getId()));
        }

        return $this->render('paragraph/new.html.twig', array(
            'paragraph' => $paragraph,
            'form' => $form->createView(),
        ));
    }

    public function showAction(Paragraph $paragraph)
    {
        $deleteForm = $this->createDeleteForm($paragraph);

        return $this->render('paragraph/show.html.twig', array(
            'paragraph' => $paragraph,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function editAction(Request $request, Paragraph $paragraph)
    {
        $deleteForm = $this->createDeleteForm($paragraph);
        $editForm = $this->createForm(ParagraphType::class, $paragraph);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paragraph);
            $em->flush();

            return $this->redirectToRoute('paragraph_edit', array('id' => $paragraph->getId()));
        }

        return $this->render('paragraph/edit.html.twig', array(
            'paragraph' => $paragraph,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, Paragraph $paragraph)
    {
        $form = $this->createDeleteForm($paragraph);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paragraph);
            $em->flush();
        }

        return $this->redirectToRoute('paragraph_index');
    }

    /**
     * @param Paragraph $paragraph The Paragraph entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Paragraph $paragraph)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('paragraph_delete', array('id' => $paragraph->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
