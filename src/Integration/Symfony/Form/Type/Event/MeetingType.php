<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Event;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Form\Type;
use Talesweaver\Application\Query;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

class MeetingType extends AbstractType implements Type\Event\Meeting
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
        $builder->add('root', EntityType::class, [
            'class' => Character::class,
            'label' => $this->getTranslationKey('root'),
            'choices' => $this->queryBus->query(new Query\Character\ForScene($scene)),
            'choice_label' => function (Character $character): string {
                return (string) $character->getName();
            },
            'constraints' => [new NotBlank()]
        ]);

        $builder->add('location', EntityType::class, [
            'class' => Location::class,
            'label' => $this->getTranslationKey('location'),
            'choices' => $this->queryBus->query(new Query\Location\ForScene($scene)),
            'choice_label' => function (Location $location): string {
                return (string) $location->getName();
            },
            'constraints' => [new NotBlank()]
        ]);

        $builder->add('relation', EntityType::class, [
            'class' => Character::class,
            'label' => $this->getTranslationKey('relation'),
            'choices' => $this->queryBus->query(new Query\Character\ForScene($scene)),
            'choice_label' => function (Character $character): string {
                return (string) $character->getName();
            },
            'constraints' => [new NotBlank()]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Meeting::class);
        $resolver->setDefault('scene', null);
        $resolver->setAllowedTypes('scene', [Scene::class]);
        $resolver->setRequired(['scene']);
    }

    private function getTranslationKey(string $field): string
    {
        return sprintf('event.%s.fields.%s', Meeting::class, $field);
    }
}
