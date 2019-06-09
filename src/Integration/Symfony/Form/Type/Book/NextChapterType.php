<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Book;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Form\Type\Book\NextChapter;
use Talesweaver\Application\Query\Chapter\EntityExists;
use Talesweaver\Domain\Book;

final class NextChapterType extends AbstractType implements NextChapter
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
        $builder->add('title', TextType::class, [
            'label' => 'chapter.title',
            'attr' => [
                'autofocus' => 'autofocus',
                'placeholder' => 'chapter.placeholder.title.book'
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
                new Callback(['callback' => $this->validationCallback($options['book'])])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);

        $resolver->setRequired(['book']);
        $resolver->setAllowedTypes('book', [Book::class]);
    }

    private function validationCallback(Book $book): callable
    {
        return function (?string $name, ExecutionContextInterface $context) use ($book): void {
            if (null === $name || '' === $name) {
                return;
            }

            if (true === $this->queryBus->query(new EntityExists($name, null, $book->getId()))) {
                $context->buildViolation('chapter.exists')->addViolation();
            }
        };
    }
}
