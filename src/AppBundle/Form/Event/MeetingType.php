<?php

namespace AppBundle\Form\Event;

use AppBundle\Entity\Character;
use AppBundle\Entity\Item;
use AppBundle\Entity\Location;
use AppBundle\Entity\Repository\CharacterRepository;
use AppBundle\Entity\Repository\ItemRepository;
use AppBundle\Entity\Repository\LocationRepository;
use AppBundle\Entity\Scene;
use AppBundle\Model\Meeting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $scene = $options['scene'];

        $builder->add('root', EntityType::class, [
            'class' => Character::class,
            'label' => $this->getTranslationKey('root'),
            'query_builder' => function (CharacterRepository $repository) use ($scene) {
                return $repository->createForSceneQueryBuilder($scene);
            }
        ]);

        $builder->add('location', EntityType::class, [
            'class' => Location::class,
            'label' => $this->getTranslationKey('location'),
            'query_builder' => function (LocationRepository $repository) use ($scene) {
                return $repository->createForSceneQueryBuilder($scene);
            }
        ]);

        $builder->add('relation', EntityType::class, [
            'class' => Character::class,
            'label' => $this->getTranslationKey('relation'),
            'query_builder' => function (CharacterRepository $repository) use ($scene) {
                return $repository->createForSceneQueryBuilder($scene);
            }
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
