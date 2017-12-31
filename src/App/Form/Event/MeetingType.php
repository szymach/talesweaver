<?php

declare(strict_types=1);

namespace App\Form\Event;

use App\Entity\Character;
use App\Entity\Location;
use App\Entity\Scene;
use App\Repository\CharacterRepository;
use App\Repository\LocationRepository;
use Domain\Event\Meeting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
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
            'query_builder' => $this->characterRepository->createForSceneQueryBuilder($scene)
        ]);

        $builder->add('location', EntityType::class, [
            'class' => Location::class,
            'label' => $this->getTranslationKey('location'),
            'query_builder' => $this->locationRepository->createForSceneQueryBuilder($scene)
        ]);

        $builder->add('relation', EntityType::class, [
            'class' => Character::class,
            'label' => $this->getTranslationKey('relation'),
            'query_builder' => $this->characterRepository->createForSceneQueryBuilder($scene)
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Meeting::class);
        $resolver->setDefault('scene', null);
        $resolver->setAllowedTypes('scene', [Scene::class]);
        $resolver->setRequired(['scene']);
    }

    private function getTranslationKey($field)
    {
        return sprintf('event.%s.fields.%s', Meeting::class, $field);
    }
}
