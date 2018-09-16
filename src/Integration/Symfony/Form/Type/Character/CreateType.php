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
use Talesweaver\Application\Character\Create\DTO;
use Talesweaver\Application\Form\Type\Character\Create;
use Talesweaver\Integration\Symfony\Repository\CharacterRepository;

class CreateType extends AbstractType implements Create
{
    /**
     * @var CharacterRepository
     */
    private $characterRepository;

    public function __construct(CharacterRepository $characterRepository)
    {
        $this->characterRepository = $characterRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sceneId = $options['sceneId'];
        $builder->add('name', TextType::class, [
            'label' => 'character.name',
            'attr' => ['autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Length(['max' => 255]), new Callback([
                'callback' => function (?string $name, ExecutionContextInterface $context) use ($sceneId): void {
                    if (null === $name || '' === $name) {
                        return;
                    }

                    if (true === $this->characterRepository->entityExists($name, null, $sceneId)) {
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
        $resolver->setDefaults([
            'attr' => ['class' => 'js-form'],
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'sceneId' => null
        ]);

        $resolver->setAllowedTypes('sceneId', ['null', UuidInterface::class]);
    }
}
