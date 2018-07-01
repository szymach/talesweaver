<?php

declare(strict_types=1);

namespace App\Form\Chapter;

use App\Repository\BookRepository;
use Application\Chapter\Create\DTO;
use Domain\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'chapter.title',
            'attr' => ['placeholder' => $options['title_placeholder'], 'autofocus' => 'autofocus']
        ]);

        $builder->add('book', EntityType::class, [
            'label' => 'chapter.book',
            'class' => Book::Class,
            'query_builder' => $this->bookRepository->createQueryBuilder(),
            'placeholder' => 'chapter.placeholder.book',
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST,
            'title_placeholder' => 'chapter.placeholder.title.standalone'
        ]);
        $resolver->setAllowedTypes('title_placeholder', ['null', 'string']);
    }
}
