<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Talesweaver\Application\Form\Type\Security\ResetPassword;

class ResetPasswordRequestType extends AbstractType implements ResetPassword\Request
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'label' => 'security.reset_password.request.email.label',
            'constraints' => [new NotBlank(), new Email()],
            'attr' => [
                'placeholder' => 'security.reset_password.request.email.placeholder',
                'autofocus' => 'autofocus'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);
    }
}
