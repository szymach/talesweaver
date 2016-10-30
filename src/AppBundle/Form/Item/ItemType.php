<?php

namespace AppBundle\Form\Item;

use AppBundle\Entity\Item;
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

        $builder->add('description', CKEditorType::class, [
            'label' => 'location.description'
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
