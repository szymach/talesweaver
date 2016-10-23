<?php

namespace AppBundle\Form\Character;

use AppBundle\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', 'ckeditor')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
            'method' => 'POST',
            'attr' => ['class' => 'js-form', 'data-container-id' => 'character']
        ]);
    }
}
