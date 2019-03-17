<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Event;

use Assert\Assertion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Form\Type;
use Talesweaver\Application\Query;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event\Death;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

final class DeathType extends AbstractType implements Type\Event\Death
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $scene = $options['scene'];
        Assertion::isInstanceOf($scene, Scene::class);

        $characters = $this->queryBus->query(new Query\Character\ForScene($scene));
        $builder->add('character', EntityType::class, [
            'class' => Character::class,
            'label' => sprintf('event.%s.fields.character', Death::class),
            'choices' => $characters,
            'choice_label' => function (Character $character): string {
                return (string) $character->getName();
            },
            'constraints' => [new NotBlank()]
        ]);

        $builder->add('location', EntityType::class, [
            'class' => Location::class,
            'label' => sprintf('event.%s.fields.location', Death::class),
            'choices' => $this->queryBus->query(new Query\Location\ForScene($scene)),
            'choice_label' => function (Location $location): string {
                return (string) $location->getName();
            },
            'constraints' => [new NotBlank()]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Death::class);
        $resolver->setDefault('scene', null);
        $resolver->setAllowedTypes('scene', [Scene::class]);
        $resolver->setRequired(['scene']);
    }
}
