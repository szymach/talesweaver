<?php

namespace AppBundle\Form\Scene;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Scene;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Piotr Szymaszek
 */
class NewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'scene.title'
        ]);

        $builder->add('text', CKEditorType::class, [
            'label' => 'scene.text'
        ]);

        $builder->add('chapter', EntityType::class, [
            'label' => 'scene.chapter',
            'class' => Chapter::class,
            'required' => false
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Scene::class
        ]);
    }
}
