<?php

namespace AppBundle\Form\Character;

use AppBundle\Entity\Character;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'character.name'
        ]);

        $builder->add('description', CKEditorType::class, [
            'label' => 'character.description'
        ]);
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
