<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Scene;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Scene\Create\DTO;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Query\Chapter\ForBook;
use Talesweaver\Application\Query\Chapter\Standalone;
use Talesweaver\Application\Query\Scene\EntityExists;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;

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
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options): void {
            $dto = $event->getData();
            $chapter = $this->getChapter($dto);
            $book = $this->getBook($chapter);

            $form = $event->getForm();
            $form->add('title', TextType::class, [
                'label' => 'scene.title',
                'attr' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $options['title_placeholder']
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                    new Callback(['callback' => $this->validationCallback($chapter)])
                ]
            ]);

            $form->add('chapter', EntityType::class, [
                'label' => 'scene.chapter',
                'class' => Chapter::Class,
                'choices' => $this->getChapterChoices($book),
                'choice_label' => $this->getChapterChoiceLabel($book),
                'placeholder' => 'scene.placeholder.chapter',
                'required' => false
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'title_placeholder' => 'scene.placeholder.title.standalone'
        ]);
        $resolver->setAllowedTypes('title_placeholder', ['string']);
    }

    private function validationCallback(?Chapter $chapter): callable
    {
        $chapterId = null !== $chapter ? $chapter->getId() : null;
        return function (?string $name, ExecutionContextInterface $context) use ($chapterId): void {
            if (null === $name || '' === $name) {
                return;
            }

            if (true === $this->queryBus->query(new EntityExists($name, null, $chapterId))) {
                $context->buildViolation('scene.exists')->addViolation();
            }
        };
    }

    private function getChapterChoices(?Book $book): array
    {
        $chapters = $this->queryBus->query(
            null !== $book ? new ForBook($book) : new Standalone()
        );

        usort($chapters, function (Chapter $a, Chapter $b) use ($book): int {
            return null !== $book
                ? $a->getPosition() <=> $b->getPosition()
                : strnatcmp((string) $a->getTitle(), (string) $b->getTitle())
            ;
        });

        return $chapters;
    }

    private function getChapterChoiceLabel(?Book $book): callable
    {
        if (null !== $book) {
            return function (Chapter $chapter): string {
                return (string) $chapter->getTitle();
            };
        } else {
            return function (Chapter $chapter): string {
                $book = $chapter->getBook();
                return null !== $book
                    ? sprintf('%s (%s)', $chapter->getTitle(), $book->getTitle())
                    : (string) $chapter->getTitle()
                ;
            };
        }
    }

    private function getChapter(?DTO $dto): ?Chapter
    {
        if (null === $dto) {
            return null;
        }

        return $dto->getChapter();
    }

    private function getBook(?Chapter $chapter): ?Book
    {
        if (null === $chapter) {
            return null;
        }

        return $chapter->getBook();
    }
}
