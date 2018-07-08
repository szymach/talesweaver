<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Form\Book;

use Talesweaver\Application\Book\Edit\DTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'book.title',
            'attr' => ['autofocus' => 'autofocus']
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
