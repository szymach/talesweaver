<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Item;

use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Talesweaver\Application\Item\Edit\DTO;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'location.name',
            'attr' => ['autofocus' => 'autofocus']
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'item.description',
            'attr' => ['class' => 'ckeditor'],
            'required' => false
        ]);

        $builder->add('avatar', RemovableFileType::class, [
            'label' => 'item.avatar',
            'file_type' => ImageType::class,
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'attr' => ['class' => 'js-form']
        ]);
    }
}
