<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use App\Repository\BookRepository;
use App\Repository\ChapterRepository;
use App\Repository\CharacterRepository;
use App\Repository\EventRepository;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Book;
use Domain\Chapter;
use Domain\Character;
use Domain\Event;
use Domain\Item;
use Domain\Location;
use Domain\Scene;
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
