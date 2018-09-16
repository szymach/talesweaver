<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Talesweaver\Application\Form\Type\Security\Register;
use Talesweaver\Integration\Symfony\Validation\Constraints\UniqueUserEmail;

class RegisterType extends AbstractType implements Register
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'label' => 'security.registration.email.label',
            'constraints' => [new NotBlank(), new Email(), new UniqueUserEmail()],
            'attr' => ['placeholder' => 'security.registration.email.placeholder', 'autofocus' => 'autofocus']
        ]);

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => [
                'label' => 'security.registration.password.label',
                'constraints' => [new NotBlank(), new Length(['min' => 6])],
                'attr' => ['placeholder' => 'security.registration.password.placeholder']
            ],
            'second_options' => [
                'label' => 'security.registration.password_repeat.label',
                'attr' => ['placeholder' => 'security.registration.password_repeat.placeholder']
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);
    }
}
