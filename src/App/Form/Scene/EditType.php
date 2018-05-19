<?php

declare(strict_types=1);

namespace App\Form\Scene;

use App\Repository\ChapterRepository;
use Domain\Entity\Chapter;
use Domain\Entity\Scene;
use Domain\Scene\Edit\DTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $form->add('title', TextType::class, [
                'label' => 'scene.title',
                'attr' => ['autofocus' => 'autofocus']
            ]);

            /* @var $data Scene */
            $scene = $event->getData();
            if (null !== $scene && null !== $scene->getChapter() && null !== $scene->getChapter()->getBook()) {
                $qb = $this->chapterRepository->createForBookQb($scene->getChapter()->getBook());
            } else {
                $qb = $this->chapterRepository->createStandaloneQueryBuilder();
            }

            $form->add('chapter', EntityType::class, [
                'label' => 'scene.chapter',
                'class' => Chapter::Class,
                'data' => null !== $scene ? $scene->getChapter() : null,
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
