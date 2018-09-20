<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Event;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Command\Event\Edit\DTO;
use Talesweaver\Application\Form\Type\Event\Edit;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\EventRepository;

class EditType extends AbstractType implements Edit
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $eventId = $options['eventId'];
        $builder->add('name', TextType::class, [
            'label' => 'event.name',
            'attr' => ['autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Length(['max' => 255]), new Callback([
                'callback' => function (?string $name, ExecutionContextInterface $context) use ($eventId): void {
                    if (null === $name || '' === $name) {
                        return;
                    }

                    if (true === $this->eventRepository->entityExists($name, $eventId, null)) {
                        $context->buildViolation('event.exists')->addViolation();
                    }
                }
            ])]
        ]);

        $builder->add('model', $options['model'], [
            'label' => false,
            'scene' => $options['scene']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'js-form'],
            'data_class' => DTO::class,
            'model' => null,
            'scene' => null,
            'eventId' => null
        ]);

        $resolver->setAllowedTypes('eventId', ['null', UuidInterface::class]);
        $resolver->setAllowedTypes('scene', [Scene::class]);
        $resolver->setAllowedTypes('model', ['string']);
        $resolver->setRequired(['scene', 'model']);
    }
}