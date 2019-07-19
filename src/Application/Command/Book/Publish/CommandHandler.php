<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Publish;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Domain\ValueObject\LongText;

final class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(HtmlContent $htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(Command $command): void
    {
        $book = $command->getBook();
        $book->publish(
            $command->getTitle(),
            LongText::fromString(
                $this->htmlContent->fromTemplate(
                    'display/publication.html.twig',
                    [
                        'title' => $command->getTitle(),
                        'chapters' => $book->getChapters(),
                        'locale' => $book->getLocale()
                    ]
                )
            ),
            $command->isVisible()
        );
    }
}
