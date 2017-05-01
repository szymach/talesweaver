<?php

namespace AppBundle\Form\Event;

use AppBundle\Entity\Event;
use AppBundle\Form\Event\MeetingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'event.name'
        ]);

        $builder->add('model', ChoiceType::class, [
            'label' => 'event.model',
            'choices' => [MeetingType::class]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class
        ]);
    }
}
