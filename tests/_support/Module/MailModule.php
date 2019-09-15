<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\TestInterface;
use Swift_Message;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use function file_get_contents;
use function key;
use function preg_replace;
use function strip_tags;
use function strpos;
use function unlink;
use function unserialize;

final class MailModule extends Module
{
    /**
     * @var ContainerModule
     */
    private $containerModule;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $from;

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);
        $this->containerModule = $moduleContainer->getModule(ContainerModule::class);
        $this->filesystem = new Filesystem();
    }

    /**
     * @phpcs:disable
     */
    public function _before(TestInterface $test): void
    {
        $this->clearSpoolFiles();
    }

    public function seeAnEmailHasBeenSent(string $subject, string $to): void
    {
        $message = $this->fetchEmail($subject, $to);
        $this->assertNotNull($message, "There is no email with \"{$subject}\" subject");
        $this->assertEquals(
            $this->getFromContainerParameter(),
            key($message->getFrom())
        );
    }

    /**
     * @param string $subject
     * @param string[] $htmlFragments
     * @param string[] $textFragments
     * @param string $to
     */
    public function seeAnEmailHasBeenSentWithBody(
        string $subject,
        array $htmlFragments,
        array $textFragments = [],
        string $to = null
    ): void {
        $message = $this->fetchEmail($subject, $to);

        $this->assertNotNull($message, "There is no email with \"{$subject}\" subject");
        $this->assertEquals(
            $this->getFromContainerParameter(),
            key($message->getFrom())
        );

        $emailBody = preg_replace('/\s+/u', ' ', $message->getBody());
        array_walk(
            $htmlFragments,
            function (string $item, $key, string $emailBody): void {
                $this->assertNotFalse(strpos($emailBody, $item));
            },
            $emailBody
        );

        array_walk(
            $textFragments,
            function (string $item, $key, $emailBody): void {
                $this->assertNotFalse(strpos($emailBody, $item));
            },
            strip_tags($emailBody)
        );
    }

    public function seeAnEmailHasNotBeenSent(string $subject): void
    {
        if (false === $this->spoolDirectoryExists()) {
            return;
        }

        $this->assertNull(
            $this->fetchEmail($subject),
            "Email with \"{$subject}\" subject should have not been sent, but has been."
        );
    }

    public function haveClearedEmailSpool(): void
    {
        $this->clearSpoolFiles();
    }

    private function fetchEmail(string $subject, string $to = null): ?Swift_Message
    {
        $files = $this->getSpoolFiles();
        $files->sortByModifiedTime();

        $messages = [];

        foreach ($files as $file) {
            $filename = (string) $file;
            $message = unserialize(file_get_contents($filename));

            if (false === $message instanceof Swift_Message || $subject !== $message->getSubject()) {
                continue;
            }

            if (null !== $to &&  false === array_key_exists($to, $message->getTo())) {
                continue;
            }

            unlink($filename);
            $messages[] = $message;
        }

        $this->assertLessThanOrEqual(1, count($messages));

        return 1 === count($messages) ? reset($messages) : null;
    }

    private function clearSpoolFiles(): void
    {
        if (false === $this->spoolDirectoryExists()) {
            return;
        }

        $this->filesystem->remove($this->getSpoolFiles());
    }

    private function getSpoolFiles(): Finder
    {
        $finder = new Finder();
        $finder->in($this->getSpoolDir())->ignoreDotFiles(true)->files();

        return $finder;
    }

    private function getFromContainerParameter(): string
    {
        if (null === $this->from) {
            $this->from = $this->containerModule->getParameter('mailer_from');
        }

        return $this->from;
    }

    private function spoolDirectoryExists(): bool
    {
        return $this->filesystem->exists($this->getSpoolDir());
    }

    private function getSpoolDir(): string
    {
        return $this->containerModule->getParameter('swiftmailer.spool.default.file.path');
    }
}
