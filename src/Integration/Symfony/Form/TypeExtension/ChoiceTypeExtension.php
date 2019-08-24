<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\TypeExtension;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChoiceTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): array
    {
        return [ChoiceType::class, EntityType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setNormalizer('attr', function (Options $options): array {
            return false === $options['expanded'] ? ['class' => 'custom-select'] : [];
        });

        $resolver->setNormalizer('label_attr', function (Options $options): array {
            if (false === $options['expanded']) {
                return [];
            }

            return [
                'class' => true === $options['multiple'] ? 'checkbox-custom' : 'radio-custom'
            ];
        });
    }
}
