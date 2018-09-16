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
use Talesweaver\Application\Form\Type\Scene\Edit;
use Talesweaver\Application\Scene\Edit\DTO;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Repository\ChapterRepository;
use Talesweaver\Integration\Symfony\Repository\SceneRepository;

class EditType extends AbstractType implements Edit
{
    /**
     * @var SceneRepository
     */
    private $sceneRepository;

    /**
     * @var ChapterRepository
     */
    private $chapterRepository;

    public function __construct(SceneRepository $sceneRepository, ChapterRepository $chapterRepository)
    {
        $this->sceneRepository = $sceneRepository;
        $this->chapterRepository = $chapterRepository;
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
                    new Callback(['callback' => $this->validationCallback($options['sceneId'])])
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
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'sceneId' => null
        ]);

        $resolver->setAllowedTypes('sceneId', [UuidInterface::class]);
        $resolver->setRequired(['sceneId']);
    }

    private function validationCallback(UuidInterface $sceneId): callable
    {
        return function (?string $name, ExecutionContextInterface $context) use ($sceneId): void {
            if (null === $name || '' === $name) {
                return;
            }

            if (true === $this->sceneRepository->entityExists($name, $sceneId, null)) {
                $context->buildViolation('scene.exists')->addViolation();
            }
        };
    }

    private function getChapterChoices(?Book $book): array
    {
        return null !== $book ? $this->chapterRepository->findForBook($book) : $this->chapterRepository->findAll();
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
