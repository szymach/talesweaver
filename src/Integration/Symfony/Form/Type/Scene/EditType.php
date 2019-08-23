<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Scene;

use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
use Talesweaver\Application\Command\Scene\Edit\DTO;
use Talesweaver\Application\Form\Type\Scene\Edit;
use Talesweaver\Application\Query\Chapter\ForBook;
use Talesweaver\Application\Query\Chapter\Standalone;
use Talesweaver\Application\Query\Scene\EntityExists;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;

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
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options): void {
            $dto = $event->getData();
            $chapter = $this->getChapter($dto);
            $book = $this->getBook($chapter);

            $form = $event->getForm();
            $form->add('title', TextType::class, [
                'label' => 'scene.title',
                'attr' => ['autofocus' => 'autofocus'],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                    $this->validationCallback($options['sceneId'])
                ]
            ]);

            $form->add('text', TextareaType::class, [
                'label' => false,
                'attr' => ['class' => 'ckeditor']
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
            'attr' => ['class' => 'autosave', 'novalidate' => 'novalidate'],
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'sceneId' => null
        ]);

        $resolver->setAllowedTypes('sceneId', [UuidInterface::class]);
        $resolver->setRequired(['sceneId']);
    }

    private function validationCallback(UuidInterface $sceneId): Callback
    {
        return new Callback([
            'callback' => function (?string $name, ExecutionContextInterface $context) use ($sceneId): void {
                if (null === $name || '' === $name) {
                    return;
                }

                if (true === $this->queryBus->query(new EntityExists($name, $sceneId, null))) {
                    $context->buildViolation('scene.exists')->addViolation();
                }
            }
        ]);
    }

    private function getChapterChoices(?Book $book): array
    {
        $hasBook = null !== $book;

        $chapters = $this->queryBus->query(
            true === $hasBook ? new ForBook($book) : new Standalone()
        );

        usort($chapters, function (Chapter $a, Chapter $b) use ($hasBook): int {
            return true === $hasBook
                ? $a->getPosition() <=> $b->getPosition()
                : strnatcmp((string) $a->getTitle(), (string) $b->getTitle())
            ;
        });

        return $chapters;
    }

    private function getChapterChoiceLabel(?Book $book): callable
    {
        if (null !== $book) {
            $label = function (Chapter $chapter) use ($book): string {
                return sprintf('%s (%s)', $chapter->getTitle(), $book->getTitle());
            };
        } else {
            $label = function (Chapter $chapter): string {
                return (string) $chapter->getTitle();
            };
        }

        return $label;
    }

    private function getBook(?Chapter $chapter): ?Book
    {
        if (null === $chapter) {
            return null;
        }

        return $chapter->getBook();
    }

    private function getChapter(?DTO $dto): ?Chapter
    {
        if (null === $dto) {
            return null;
        }

        return $dto->getChapter();
    }
}
