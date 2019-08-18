<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Application\Security\AuthorContext;

final class UpdateAuthorPeronalInformationHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(AuthorContext $authorContext)
    {
        $this->authorContext = $authorContext;
    }

    public function __invoke(UpdateAuthorPeronalInformation $command): void
    {
        $this->authorContext->getAuthor()->updatePersonalInformation(
            $command->getName(),
            $command->getSurname()
        );
    }
}
