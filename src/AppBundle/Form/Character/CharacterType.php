<?php

namespace AppBundle\Form\Character;

use AppBundle\Entity\Character;
use AppBundle\Entity\Item;
use AppBundle\Entity\Location;
use AppBundle\Entity\Repository\ItemRepository;
use AppBundle\Entity\Repository\LocationRepository;
use Doctrine\Common\Collections\Collection;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'character.name'
        ]);

        $builder->add('avatar', RemovableFileType::class, [
            'label' => 'character.avatar',
            'file_type' => ImageType::class,
            'required' => false
        ]);

        $builder->add('description', CKEditorType::class, [
            'label' => 'character.description',
            'config_name' => 'small_size',
            'required' => false
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if (!$data) {
                return;
            }

            /* @var $scenes Collection */
            $scenes = $data->getScenes();
            $form = $event->getForm();
            if (!count($scenes)) {
                return;
            }

            $form->add('items', EntityType::class, [
                'label' => 'character.items',
                'query_builder' => function (ItemRepository $repository) use ($scenes) {
                    return $repository->createRelatedToScenesQueryBuilder($scenes->toArray());
                },
                'class' => Item::Class,
                'required' => false,
                'multiple' => true,
                'expanded' => true
            ]);

            $form->add('locations', EntityType::class, [
                'label' => 'character.locations',
                'query_builder' => function (LocationRepository $repository) use ($scenes) {
                    return $repository->createRelatedToScenesQueryBuilder($scenes->toArray());
                },
                'class' => Location::Class,
                'required' => false,
                'multiple' => true,
                'expanded' => true
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
            'method' => 'POST',
            'attr' => ['class' => 'js-form']
        ]);
    }
}
