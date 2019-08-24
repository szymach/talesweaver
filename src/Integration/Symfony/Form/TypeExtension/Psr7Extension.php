<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\TypeExtension;

use Talesweaver\Integration\Symfony\Form\Psr7FormRequestHandler;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Psr7Extension extends AbstractTypeExtension
{
    /**
     * @var Psr7FormRequestHandler
     */
    private $formRequestHandler;

    public function __construct(Psr7FormRequestHandler $formRequestHandler)
    {
        $this->formRequestHandler = $formRequestHandler;
    }

    public static function getExtendedTypes(): array
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('psr7', false);
        $resolver->setAllowedTypes('psr7', 'bool');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (true === $options['psr7']) {
            $builder->setRequestHandler($this->formRequestHandler);
        }
    }
}
