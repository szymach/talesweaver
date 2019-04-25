<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormHandlerInterface;

final class FormHandlerFactory implements FormHandlerFactoryInterface
{
    /**
     * @var FormClassResolver
     */
    private $formClassResolver;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormClassResolver $formClassResolver, FormFactoryInterface $formFactory)
    {
        $this->formClassResolver = $formClassResolver;
        $this->formFactory = $formFactory;
    }

    public function createWithRequest(
        ServerRequestInterface $request,
        string $type,
        $data = null,
        array $options = []
    ): FormHandlerInterface {
        return new FormHandler(
            $this->formFactory->create(
                $this->getFormClass($type),
                $data,
                array_merge(['psr7' => true], $options)
            ),
            $request
        );
    }

    private function getFormClass(string $type): string
    {
        return $this->formClassResolver->resolve($type);
    }
}
