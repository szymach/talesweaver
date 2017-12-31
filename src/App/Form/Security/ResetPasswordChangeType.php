<?php

declare(strict_types=1);

namespace App\Form\Security;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'security.reset_password.change.first_password.label',
                'constraints' => [new NotBlank(), new Length(['min' => 6])],
                'attr' => [
                    'placeholder' => 'security.reset_password.change.first_password.placeholder'
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
