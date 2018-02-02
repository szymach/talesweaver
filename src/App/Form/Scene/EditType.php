<?php

declare(strict_types=1);

namespace App\Form\Scene;

use App\Entity\Chapter;
use Domain\Scene\Edit\DTO;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => 'scene.title']);

        $builder->add('chapter', EntityType::class, [
            'label' => 'scene.chapter',
            'class' => Chapter::Class,
            'required' => false
        ]);

        $builder->add('text', CKEditorType::class, ['label' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTO::class,
            'method' => Request::METHOD_POST
        ]);
    }
}
