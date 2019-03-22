<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Event;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Event\Create\DTO;
use Talesweaver\Application\Form\Type\Event\Create;
use Talesweaver\Application\Query\Event\EntityExists;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

class CreateType extends AbstractType implements Create
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
        $builder->add('name', TextType::class, [
            'label' => 'event.name',
            'attr' => ['autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Length(['max' => 255]), new Callback([
                'callback' => function (?string $name, ExecutionContextInterface $context) use ($scene): void {
                    if (null === $name || '' === $name) {
                        return;
                    }

                    if (true === $this->queryBus->query(new EntityExists($name, null, $scene->getId()))) {
                        $context->buildViolation('event.exists')->addViolation();
                    }
                }
            ])]
        ]);

        $builder->add('characters', EntityType::class, [
            'class' => Character::class,
            'choices' => $options['characters'],
            'choice_label' => function (Character $choice): string {
                return (string) $choice->getName();
            },
            'multiple' => true,
            'expanded' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'js-form'],
            'characters' => [],
            'data_class' => DTO::class,
            'scene' => null
        ]);

        $resolver->setAllowedTypes('characters', ['array']);
        $resolver->setRequired(['characters']);

        $resolver->setAllowedTypes('scene', [Scene::class]);
        $resolver->setRequired(['scene']);
    }
}
