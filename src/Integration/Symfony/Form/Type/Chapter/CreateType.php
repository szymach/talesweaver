<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Chapter;

use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
use Talesweaver\Application\Command\Chapter\Create\DTO;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Query\Book\AllBooks;
use Talesweaver\Application\Query\Chapter\EntityExists;
use Talesweaver\Domain\Book;

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
        $bookId = $options['bookId'];
        $builder->add('title', TextType::class, [
            'label' => 'chapter.title',
            'attr' => ['placeholder' => $options['title_placeholder'], 'autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Callback([
                'callback' => function (?string $title, ExecutionContextInterface $context) use ($bookId): void {
                    if (null === $title || '' === $title) {
                        return;
                    }

                    if (true === $this->queryBus->query(new EntityExists($title, null, $bookId))) {
                        $context->buildViolation('chapter.exists')->addViolation();
                    }
                }
            ])]
        ]);

        $builder->add('preface', TextareaType::class, [
            'label' => 'chapter.preface',
            'attr' => ['class' => 'ckeditor'],
            'required' => false
        ]);

        $builder->add('book', EntityType::class, [
            'label' => 'chapter.book',
            'class' => Book::class,
            'choices' => $this->getBookChoices(),
            'choice_label' => function (Book $book): string {
                return (string) $book->getTitle();
            },
            'placeholder' => 'chapter.placeholder.book',
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'ckeditor-small'],
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'title_placeholder' => 'chapter.placeholder.title.standalone',
            'bookId' => null
        ]);

        $resolver->setAllowedTypes('bookId', ['null', UuidInterface::class]);
        $resolver->setAllowedTypes('title_placeholder', ['null', 'string']);
    }

    private function getBookChoices(): array
    {
        $books = $this->queryBus->query(new AllBooks());

        usort($books, function (Book $a, Book $b): int {
            return strnatcmp((string) $a->getTitle(), (string) $b->getTitle());
        });

        return $books;
    }
}
