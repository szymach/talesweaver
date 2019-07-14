<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Publish;

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
        $scene = $command->getScene();
        $scene->publish(
            $command->getTitle(),
            LongText::fromString(
                $this->htmlContent->fromTemplate(
                    'display/standalone.html.twig',
                    [
                        'title' => $command->getTitle(),
                        'text' => $scene->getText(),
                        'locale' => $scene->getLocale()
                    ]
                )
            ),
            $command->isVisible()
        );
    }
}
