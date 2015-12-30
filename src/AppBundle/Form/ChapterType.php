<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Book;

/**
 * @author Piotr Szymaszek
 */
class ChapterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_book_select']) {
            $builder->add('book', EntityType::class, [
                'label' => 'chapter.book',
                'class' => Book::class,
                'choice_label' => 'title',
                'required' => false
            ]);
        }

        $builder->add('title', TextType::class, [
            'label' => 'chapter.title'
        ]);
        
        if ($options['allow_book_select']) {
            $builder->add('scenes', CollectionType::class, [
                'label' => 'chapter.scenes',
                'entry_type' => SceneType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'entry_options' => [
                    'allow_chapter_select' => false
                ]
            ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Chapter',
            'allow_book_select' => true
        ]);
    }
}
