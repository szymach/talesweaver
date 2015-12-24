<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'book.title'
        ]);

        $builder->add('chapters', CollectionType::class, [
            'label' => 'book.chapters',
            'type' => ChapterType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'by_reference' => false,
            'entry_options' => [
                'allow_book_select' => false
            ]
        ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Book'
        ]);
    }
}
