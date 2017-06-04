<?php

namespace AppBundle\Form\Book;

use AppBundle\Book\Edit\DTO;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => 'book.title']);

        $builder->add('description', CKEditorType::class, [
            'label' => 'book.description',
            'required' => false,
            'config_name' => 'small_size'
        ]);

//        $builder->add('introduction', CKEditorType::class, [
//            'label' =>  false,
//            'required' => false
//        ]);
//
//        $builder->add('expansion', CKEditorType::class, [
//            'label' =>  false,
//            'required' => false
//        ]);
//
//        $builder->add('ending', CKEditorType::class, [
//            'label' =>  false,
//            'required' => false
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST
        ]);
    }
}
