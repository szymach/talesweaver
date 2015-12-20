<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Chapter;

class SectionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_chapter_select']) {
            $builder->add('chapter', EntityType::class, [
                'label' => 'section.chapter',
                'class' => Chapter::class,
                'choice_label' => 'title',
                'required' => false
            ]);
        }

        $builder->add('title', TextType::class, [
            'label' => 'section.title'
        ]);

        $builder->add('text', TextareaType::class, [
            'label' => 'section.text'
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Section',
            'allow_chapter_select' => true
        ));
    }
}
