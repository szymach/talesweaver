<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', EmailType::class, [
            'label' => 'security.reset_password.request.username.label',
            'constraints' => [new NotBlank(), new Email()],
            'attr' => [
                'placeholder' => 'security.reset_password.request.username.placeholder',
                'autofocus' => 'autofocus'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);
    }
}
