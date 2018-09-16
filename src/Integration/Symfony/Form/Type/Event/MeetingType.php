<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Event;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Talesweaver\Application\Form\Type;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\CharacterRepository;
use Talesweaver\Integration\Symfony\Repository\LocationRepository;

class MeetingType extends AbstractType implements Type\Event\Meeting
{
    /**
     * @var CharacterRepository
     */
    private $characterRepository;

    /**
     * @var LocationRepository
     */
    private $locationRepository;

    public function __construct(
        CharacterRepository $characterRepository,
        LocationRepository $locationRepository
    ) {
        $this->characterRepository = $characterRepository;
        $this->locationRepository = $locationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $scene = $options['scene'];
        $builder->add('root', EntityType::class, [
            'class' => Character::class,
            'label' => $this->getTranslationKey('root'),
            'choices' => $this->characterRepository->findForScene($scene),
            'choice_label' => function (Character $character): string {
                return (string) $character->getName();
            },
            'constraints' => [new NotBlank()]
        ]);

        $builder->add('location', EntityType::class, [
            'class' => Location::class,
            'label' => $this->getTranslationKey('location'),
            'choices' => $this->locationRepository->findForScene($scene),
            'choice_label' => function (Location $location): string {
                return (string) $location->getName();
            },
            'constraints' => [new NotBlank()]
        ]);

        $builder->add('relation', EntityType::class, [
            'class' => Character::class,
            'label' => $this->getTranslationKey('relation'),
            'choices' => $this->characterRepository->findForScene($scene),
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
