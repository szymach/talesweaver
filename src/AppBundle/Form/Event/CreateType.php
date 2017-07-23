<?php

namespace AppBundle\Form\Event;

use AppBundle\Entity\Scene;
use AppBundle\Event\Create\DTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'event.name'
        ]);

        $builder->add('model', $options['model'], [
            'label' => false,
            'scene' => $options['scene']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'model' => null,
            'scene' => null,
            'attr' => ['class' => 'js-form']
        ]);
        $resolver->setAllowedTypes('scene', [Scene::class]);
        $resolver->setAllowedTypes('model', ['string']);
        $resolver->setRequired(['scene', 'model']);
    }
}
