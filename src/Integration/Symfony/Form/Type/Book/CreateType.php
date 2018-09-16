<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Book;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Book\Create\DTO;
use Talesweaver\Application\Form\Type\Book\Create;
use Talesweaver\Integration\Symfony\Repository\BookRepository;

class CreateType extends AbstractType implements Create
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'book.title',
            'attr' => ['placeholder' => 'book.placeholder.title', 'autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Callback([
                'callback' => function (?string $title, ExecutionContextInterface $context): void {
                    if (null === $title || '' === $title) {
                        return;
                    }

                    if (true === $this->bookRepository->entityExists($title, null)) {
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
            'method' => Request::METHOD_POST
        ]);
    }
}
