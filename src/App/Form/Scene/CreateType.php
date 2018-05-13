<?php

declare(strict_types=1);

namespace App\Form\Scene;

use App\Repository\ChapterRepository;
use Domain\Entity\Chapter;
use Domain\Entity\Scene;
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

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /* @var $data Scene */
            $scene = $event->getData();
            $chapter = $scene->getChapter();

            $qb = null !== $chapter && null !== $chapter->getBook()
                ? $this->chapterRepository->createForBookQb($chapter->getBook())
                : $this->chapterRepository->createStandaloneQueryBuilder()
            ;
            $event->getForm()->add('chapter', EntityType::class, [
                'label' => 'scene.chapter',
                'class' => Chapter::Class,
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
