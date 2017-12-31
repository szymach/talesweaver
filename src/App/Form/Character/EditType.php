<?php

declare(strict_types=1);

namespace App\Form\Character;

use Domain\Character\Edit\DTO;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
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
        $builder->add('name', TextType::class, [
            'label' => 'character.name'
        ]);

        $builder->add('avatar', RemovableFileType::class, [
            'label' => 'character.avatar',
            'file_type' => ImageType::class,
            'required' => false
        ]);

        $builder->add('description', CKEditorType::class, [
            'label' => 'character.description',
            'config_name' => 'small_size',
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
