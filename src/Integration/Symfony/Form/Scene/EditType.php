<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Scene;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Talesweaver\Application\Scene\Edit\DTO;
use Talesweaver\Domain\Chapter;
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
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
            $form = $event->getForm();
            $form->add('title', TextType::class, [
                'label' => 'scene.title',
                'attr' => ['autofocus' => 'autofocus']
            ]);

            /* @var $scene DTO */
            $scene = $event->getData();
            if (null !== $scene && null !== $scene->getChapter() && null !== $scene->getChapter()->getBook()) {
                $qb = $this->chapterRepository->createForBookQb($scene->getChapter()->getBook());
                $choiceLabel = function (Chapter $chapter): string {
                    return (string) $chapter->getTitle();
                };
            } else {
                $qb = $this->chapterRepository->createAllAvailableQueryBuilder();
                $choiceLabel = function (Chapter $chapter): string {
                    $book = $chapter->getBook();
                    return null !== $book
                        ? sprintf('%s (%s)', $chapter->getTitle(), $book->getTitle())
                        : (string) $chapter->getTitle()
                    ;
                };
            }

            $form->add('chapter', EntityType::class, [
                'label' => 'scene.chapter',
                'class' => Chapter::Class,
                'choice_label' => $choiceLabel,
                'query_builder' => $qb,
                'placeholder' => 'scene.placeholder.chapter',
                'required' => false
            ]);

            $form->add('text', TextareaType::class, [
                'label' => false,
                'attr' => ['class' => 'ckeditor']
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST
        ]);
    }
}
