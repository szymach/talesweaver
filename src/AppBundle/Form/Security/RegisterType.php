<?php

namespace AppBundle\Form\Security;

use AppBundle\Validation\Constraints\UniqueUserEmail;
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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', EmailType::class, [
            'label' => 'security.username.label',
            'constraints' => [new NotBlank(), new Email(), new UniqueUserEmail()]
        ]);

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => [
                'label' => 'security.password.label',
                'constraints' => [new NotBlank(), new Length(['min' => 6])]
            ],
            'second_options' => ['label' => 'security.password_repeat.label']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);
    }
}
