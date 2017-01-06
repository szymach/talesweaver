<?php

namespace AppBundle\Form\Book;

use AppBundle\Entity\Book;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Piotr Szymaszek
 */
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

        $builder->add('description', CKEditorType::class, [
            'label' => 'book.description',
            'required' => false
        ]);

        $builder->add('introduction', CKEditorType::class, [
            'label' =>  false,
            'required' => false
        ]);

        $builder->add('expansion', CKEditorType::class, [
            'label' =>  false,
            'required' => false
        ]);

        $builder->add('ending', CKEditorType::class, [
            'label' =>  false,
            'required' => false
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class
        ]);
    }
}
