<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Book;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Book\Edit\DTO;
use Talesweaver\Application\Form\Type\Book\Edit;
use Talesweaver\Application\Query\Book\EntityExists;

final class EditType extends AbstractType implements Edit
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
        $id = $options['bookId'];
        $builder->add('title', TextType::class, [
            'label' => 'book.title',
            'attr' => ['autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Callback([
                'callback' => function (?string $title, ExecutionContextInterface $context) use ($id): void {
                    if (null === $title || '' === $title) {
                        return;
                    }

                    if (true === $this->queryBus->query(new EntityExists($title, $id))) {
                        $context->buildViolation('book.exists')->addViolation();
                    }
                }
            ])]
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'book.description',
            'attr' => ['class' => 'ckeditor'],
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'ckeditor-small'],
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'bookId' => null
        ]);

        $resolver->setAllowedTypes('bookId', [UuidInterface::class]);
        $resolver->setRequired(['bookId']);
    }
}
