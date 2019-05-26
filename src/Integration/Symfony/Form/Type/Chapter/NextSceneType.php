<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type\Chapter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Form\Type\Chapter\NextScene;
use Talesweaver\Application\Query\Scene\EntityExists;
use Talesweaver\Domain\Chapter;

final class NextSceneType extends AbstractType implements NextScene
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'scene.title',
            'attr' => [
                'autofocus' => 'autofocus',
                'placeholder' => 'scene.placeholder.title.chapter'
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
                new Callback(['callback' => $this->validationCallback($options['chapter'])])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('method', Request::METHOD_POST);

        $resolver->setRequired(['chapter']);
        $resolver->setAllowedTypes('chapter', [Chapter::class]);
    }

    private function validationCallback(Chapter $chapter): callable
    {
        return function (?string $name, ExecutionContextInterface $context) use ($chapter): void {
            if (null === $name || '' === $name) {
                return;
            }

            if (true === $this->queryBus->query(new EntityExists($name, null, $chapter->getId()))) {
                $context->buildViolation('scene.exists')->addViolation();
            }
        };
    }
}
