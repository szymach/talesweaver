<?php

declare(strict_types=1);

namespace App\Form\Scene;

use App\Repository\ChapterRepository;
use Doctrine\ORM\QueryBuilder;
use Domain\Entity\Chapter;
use Domain\Scene\Create\DTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
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
        $builder->add('title', TextType::class, [
            'label' => 'scene.title',
            'attr' => ['placeholder' => $options['title_placeholder'], 'autofocus' => 'autofocus']
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /* @var $data DTO */
            $scene = $event->getData();
            if (null !== $scene && null !== $scene->getChapter() && null !== $scene->getChapter()->getBook()) {
                $data = $scene->getChapter();
                $qb = $this->chapterRepository->createForBookQb($scene->getChapter()->getBook());
            } else {
                $data = null;
                $qb = $this->chapterRepository->createStandaloneQueryBuilder();
            }

            $this->addChapterField($event->getForm(), $data, $qb);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $scene = $event->getData();
            $chapterId = $scene['chapter'] ?? null;
            if (null !== $chapterId) {
                $chapter = $this->chapterRepository->find($chapterId);
                if (null !== $chapter && null !== $chapter->getBook()) {
                    $data = $chapter;
                    $qb = $this->chapterRepository->createForBookQb($chapter->getBook());
                } else {
                    $data = null;
                    $qb = $this->chapterRepository->createStandaloneQueryBuilder();
                }
            } else {
                $data = null;
                $qb = $this->chapterRepository->createStandaloneQueryBuilder();
            }

            $this->addChapterField($event->getForm(), $data, $qb);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'title_placeholder' => 'scene.placeholder.title.standalone'
        ]);
        $resolver->setAllowedTypes('title_placeholder', ['null', 'string']);
    }

    /**
     * @param FormBuilderInterface|FormInterface $builder
     * @param Chapter|null $data
     * @param QueryBuilder $qb
     * @return void
     */
    private function addChapterField($builder, ?Chapter $data, QueryBuilder $qb): void
    {
        $builder->add('chapter', EntityType::class, [
            'label' => 'scene.chapter',
            'class' => Chapter::Class,
            'data' => $data,
            'query_builder' => $qb,
            'placeholder' => 'scene.placeholder.chapter',
            'required' => false
        ]);
    }
}
