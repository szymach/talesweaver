<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Validation\Constraints;

use Talesweaver\Integration\Repository\BookRepository;
use Talesweaver\Integration\Repository\ChapterRepository;
use Talesweaver\Integration\Repository\CharacterRepository;
use Talesweaver\Integration\Repository\EventRepository;
use Talesweaver\Integration\Repository\ItemRepository;
use Talesweaver\Integration\Repository\LocationRepository;
use Talesweaver\Integration\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueDTOValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $repositories;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(
        BookRepository $bookRepository,
        ChapterRepository $chapterRepository,
        SceneRepository $sceneRepository,
        CharacterRepository $characterRepository,
        ItemRepository $itemRepository,
        LocationRepository $locationRepository,
        EventRepository $eventRepository,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->repositories = [
            Book::class => $bookRepository,
            Chapter::class => $chapterRepository,
            Scene::class => $sceneRepository,
            Character::class => $characterRepository,
            Item::class => $itemRepository,
            Location::class => $locationRepository,
            Event::class => $eventRepository
        ];
        $this->propertyAccessor = $propertyAccessor;
    }

    public function validate($dto, Constraint $constraint)
    {
        if (!is_object($dto)) {
            return;
        }

        $criteria = [];
        foreach ($constraint->fields as $field => $dqlField) {
            $criteria[$dqlField] = $this->propertyAccessor->getValue($dto, $field);
        }

        $id = null;
        if ($constraint->id) {
            $id = $this->propertyAccessor->getValue($dto, $constraint->id);
        }
        if ($this->repositories[$constraint->entityClass]->{$constraint->repositoryMethod}($criteria, $id)) {
            $violation = $this->context->buildViolation($constraint->message);
            if ($constraint->path) {
                $violation->atPath($constraint->path);
            }
            $violation->addViolation();
        }
    }
}
