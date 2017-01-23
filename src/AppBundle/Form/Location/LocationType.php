<?php

namespace AppBundle\Form\Location;

use AppBundle\Entity\Item;
use AppBundle\Entity\Location;
use AppBundle\Entity\Repository\ItemRepository;
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

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'location.name'
        ]);

        $builder->add('avatar', RemovableFileType::class, [
            'label' => 'location.avatar',
            'file_type' => ImageType::class,
            'required' => false
        ]);

        $builder->add('description', CKEditorType::class, [
            'label' => 'location.description',
            'required' => false
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /* @var $scenes Collection */
            $scenes = $event->getData()->getScenes();
            if (!count($scenes)) {
                return;
            }

            $event->getForm()->add('items', EntityType::class, [
                'label' => 'location.items',
                'query_builder' => function (ItemRepository $repository) use ($scenes) {
                    return $repository->createRelatedToScenesQueryBuilder($scenes->toArray());
                },
                'class' => Item::Class,
                'required' => false,
                'multiple' => true,
                'expanded' => true
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'method' => 'POST',
            'attr' => ['class' => 'js-form']
        ]);
    }
}
