<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Character;

use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Character\Create\DTO;
use Talesweaver\Application\Form\Type\Character\Create;
use Talesweaver\Application\Query\Character\EntityExists;
use Talesweaver\Domain\Scene;

final class CreateType extends AbstractType implements Create
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
        /** @var Scene $scene */
        $scene = $options['scene'];
        $builder->add('name', TextType::class, [
            'label' => 'character.name',
            'attr' => ['autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Length(['max' => 255]), new Callback([
                'callback' => function (?string $name, ExecutionContextInterface $context) use ($scene): void {
                    if (null === $name || '' === $name) {
                        return;
                    }

                    if (true === $this->queryBus->query(new EntityExists($name, null, $scene))) {
                        $context->buildViolation('character.exists')->addViolation();
                    }
                }
            ])]
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'character.description',
            'attr' => ['class' => 'ckeditor'],
            'required' => false
        ]);

        $builder->add('avatar', RemovableFileType::class, [
            'label' => 'character.avatar',
            'file_type' => ImageType::class,
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['scene']);
        $resolver->setDefaults([
            'attr' => ['class' => 'js-form ckeditor-small'],
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST
        ]);

        $resolver->setAllowedTypes('scene', [Scene::class]);
    }
}
