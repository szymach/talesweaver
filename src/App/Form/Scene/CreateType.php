<?php

declare(strict_types=1);

namespace App\Form\Scene;

use App\Repository\ChapterRepository;
use Domain\Entity\Chapter;
use Domain\Scene\Create\DTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
            /* @var $scene DTO */
            $scene = $event->getData();
            if (null !== $scene && null !== $scene->getChapter() && null !== $scene->getChapter()->getBook()) {
                $qb = $this->chapterRepository->createForBookQb($scene->getChapter()->getBook());
                $choiceLabel = function (Chapter $chapter): string {
                    return $chapter->getTitle();
                };
            } else {
                $qb = $this->chapterRepository->createAllAvailableQueryBuilder();
                $choiceLabel = function (Chapter $chapter): string {
                    $book = $chapter->getBook();
                    return null !== $book
                        ? sprintf('%s (%s)', $chapter->getTitle(), $book->getTitle())
                        : $chapter->getTitle()
                    ;
                };
            }

            $event->getForm()->add('chapter', EntityType::class, [
                'label' => 'scene.chapter',
                'class' => Chapter::Class,
                'choice_label' => $choiceLabel,
                'query_builder' => $qb,
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
        $resolver->setAllowedTypes('title_placeholder', ['null', 'string']);
    }
}
