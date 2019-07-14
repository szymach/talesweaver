<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Scene;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Talesweaver\Application\Command\Scene\Publish\DTO;
use Talesweaver\Application\Form\Type\Scene\Publish;

final class PublishType extends AbstractType implements Publish
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'publication.title',
            'constraints' => [new Length(['max' => 255])],
            'required' => false
        ]);

        $builder->add('visible', CheckboxType::class, [
            'label' => 'publication.visible',
            'attr' => ['class' => 'checkbox-custom'],
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('attr', ['class' => 'js-form']);
        $resolver->setDefault('data_class', DTO::class);
        $resolver->setDefault('method', Request::METHOD_POST);
    }
}
