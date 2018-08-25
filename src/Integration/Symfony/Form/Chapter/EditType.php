<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Chapter;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Chapter\Edit\DTO;
use Talesweaver\Integration\Symfony\Repository\ChapterRepository;

class EditType extends AbstractType
{
    /**
     * @var ChapterRepository
     */
    private $chapterRepository;

    public function __construct(ChapterRepository $chapterRepository)
    {
        $this->chapterRepository = $chapterRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $bookId = $options['bookId'];
        $chapterId = $options['chapterId'];
        $builder->add('title', TextType::class, [
            'label' => 'chapter.title',
            'attr' => ['autofocus' => 'autofocus'],
            'constraints' => [new NotBlank(), new Length(['max' => 255]), new Callback([
                'callback' => function (
                    ?string $title,
                    ExecutionContextInterface $context
                ) use (
                    $bookId,
                    $chapterId
                ): void {
                    if (null === $title || '' === $title) {
                        return;
                    }

                    if (true === $this->chapterRepository->entityExists($title, $chapterId, $bookId)) {
                        $context->buildViolation('chapter.exists')->addViolation();
                    }
                }
            ])]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'bookId' => null,
            'chapterId' => null
        ]);

        $resolver->setAllowedTypes('bookId', ['null', UuidInterface::class]);
        $resolver->setAllowedTypes('chapterId', [UuidInterface::class]);
        $resolver->setRequired(['chapterId']);
    }
}
