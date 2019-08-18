<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Talesweaver\Application\Command\Security\DTO\ProfileDTO;
use Talesweaver\Application\Form\Type\Security\Profile;

final class ProfileType extends AbstractType implements Profile
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'security.profile.name.label',
            'attr' => ['autofocus' => 'autofocus', 'placeholder' => 'security.profile.name.placeholder'],
            'constraints' => [new Length(['max' => 255])],
            'empty_data' => null,
            'required' => false
        ]);

        $builder->add('surname', TextType::class, [
            'label' => 'security.profile.surname.label',
            'attr' => ['placeholder' => 'security.profile.surname.placeholder'],
            'constraints' => [new Length(['max' => 255])],
            'empty_data' => null,
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', ProfileDTO::class);
        $resolver->setDefault('method', Request::METHOD_POST);
    }
}
