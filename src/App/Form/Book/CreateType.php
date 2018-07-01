<?php

declare(strict_types=1);

namespace App\Form\Book;

use Application\Book\Create\DTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'book.title',
            'attr' => ['placeholder' => 'book.placeholder.title', 'autofocus' => 'autofocus']
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'book.description',
            'attr' => ['class' => 'ckeditor'],
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'ckeditor-small'],
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST
        ]);
    }
}
