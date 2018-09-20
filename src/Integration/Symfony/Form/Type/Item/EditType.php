<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Item;

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
use Talesweaver\Application\Form\Type\Item\Edit;
use Talesweaver\Application\Command\Item\Edit\DTO;
use Talesweaver\Integration\Symfony\Repository\ItemRepository;

class EditType extends AbstractType implements Edit
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $itemId = $options['itemId'];
        $builder->add('name', TextType::class, [
            'label' => 'location.name',
            'attr' => ['autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Length(['max' => 255]), new Callback([
                'callback' => function (?string $name, ExecutionContextInterface $context) use ($itemId): void {
                    if (null === $name || '' === $name) {
                        return;
                    }

                    if (true === $this->itemRepository->entityExists($name, $itemId, null)) {
                        $context->buildViolation('item.exists')->addViolation();
                    }
                }
            ])]
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'item.description',
            'attr' => ['class' => 'ckeditor'],
            'required' => false
        ]);

        $builder->add('avatar', RemovableFileType::class, [
            'label' => 'item.avatar',
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
            'itemId' => null
        ]);

        $resolver->setAllowedTypes('itemId', [UuidInterface::class]);
        $resolver->setRequired(['itemId']);
    }
}