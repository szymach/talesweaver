<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Security\Request;

use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Talesweaver\Integration\Repository\Interfaces\FindableByIdRepository;

class SecuredInstanceParamConverter implements ParamConverterInterface
{
    /**
     * @var FindableByIdRepository[]
     */
    private $repositories;

    public function __construct(array $repositories)
    {
        $this->repositories = array_reduce(
            $repositories,
            function (array $accumulator, FindableByIdRepository $repository): array {
                $accumulator[$repository->getClassName()] = $repository;
                return $accumulator;
            },
            []
        );
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $id = $this->getId($request, $configuration);
        if (null === $id) {
            throw new NotFoundHttpException(sprintf(
                'No "id" paramater found in path "%s"',
                $request->getRequestUri()
            ));
        }

        if (false === Uuid::isValid($id)) {
            throw new NotFoundHttpException(sprintf(
                'Invalid UUID "%s" for class "%s"!',
                $id,
                $configuration->getClass()
            ));
        }

        $object = $this->find($configuration->getClass(), $id);
        if (null === $object) {
            throw new NotFoundHttpException(sprintf(
                'Could not find object of class "%s" for id "%s"',
                $configuration->getClass(),
                $id
            ));
        }

        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return in_array($configuration->getClass(), array_keys($this->repositories), true);
    }

    private function find(string $class, string $id): ?object
    {
        return $this->repositories[$class]->find($id);
    }

    private function getId(Request $request, ParamConverter $configuration)
    {
        return $request->attributes->get($configuration->getOptions()['id'] ?? 'id', null);
    }
}
