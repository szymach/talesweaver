<?php

declare(strict_types=1);

namespace App\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('currentPassword', PasswordType::class, [
            'label' => 'security.change_password.current_password.label',
            'constraints' => [new UserPassword()],
            'attr' => ['placeholder' => 'security.change_password.current_password.placeholder']
        ]);

        $builder->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => [
                'label' => 'security.change_password.new_password.first.label',
                'constraints' => [new NotBlank(), new Length(['min' => 6])],
                'attr' => ['placeholder' => 'security.change_password.new_password.first.placeholder']
            ],
            'second_options' => [
                'label' => 'security.change_password.new_password.second.label',
                'attr' => ['placeholder' => 'security.change_password.new_password.second.placeholder']
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);
        $resolver->setDefault('attr', ['novalidate' => 'novalidate']);
    }
}
