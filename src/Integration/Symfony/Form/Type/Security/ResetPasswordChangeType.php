<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Talesweaver\Application\Form\Type\Security\ResetPassword\Change;

class ResetPasswordChangeType extends AbstractType implements Change
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'security.reset_password.change.first_password.label',
                'constraints' => [new NotBlank(), new Length(['min' => 6])],
                'attr' => [
                    'placeholder' => 'security.reset_password.change.first_password.placeholder',
                    'autofocus' => 'autofocus'
                ]
            ],
            'second_options' => [
                'label' => 'security.reset_password.change.second_password.label',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'placeholder' => 'security.reset_password.change.second_password.placeholder'
                ]
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);
    }
}
