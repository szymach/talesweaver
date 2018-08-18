<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Scene;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Talesweaver\Application\Scene\Create\DTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Repository\ChapterRepository;

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
                $choices = $this->chapterRepository->findForBook($scene->getChapter()->getBook());
                $choiceLabel = function (Chapter $chapter): string {
                    return (string) $chapter->getTitle();
                };
            } else {
                $choices = $this->chapterRepository->findAll();
                $choiceLabel = function (Chapter $chapter): string {
                    $book = $chapter->getBook();
                    return null !== $book
                        ? sprintf('%s (%s)', $chapter->getTitle(), $book->getTitle())
                        : (string) $chapter->getTitle()
                    ;
                };
            }

            $event->getForm()->add('chapter', EntityType::class, [
                'label' => 'scene.chapter',
                'class' => Chapter::Class,
                'choices' => $choices,
                'choice_label' => $choiceLabel,
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
