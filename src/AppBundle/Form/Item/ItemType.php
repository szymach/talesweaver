<?php

namespace AppBundle\Form\Item;

use AppBundle\Entity\Item;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'location.name'
        ]);

        $builder->add('avatar', RemovableFileType::class, [
            'label' => 'item.avatar',
            'file_type' => ImageType::class,
            'required' => false
        ]);

        $builder->add('description', CKEditorType::class, [
            'label' => 'location.description',
            'config_name' => 'small_size',
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'method' => 'POST',
            'attr' => ['class' => 'js-form']
        ]);
    }
}
