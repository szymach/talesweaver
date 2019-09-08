<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Command;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Security\AddAdministrator;
use Talesweaver\Domain\Administrator;
use Talesweaver\Domain\ValueObject\Email;
use function filter_var;
use function is_string;
use function mb_strlen;

final class AddAdministratorCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email used for logging in.')
            ->setDescription(
                'This command can add new administrators. These are inactive by default'
                . ' and need to be manually activated by another command.'
            )
            ->setName('talesweaver:admin:add')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        /** @var string $email */
        $email = $input->getArgument('email');
        if (false === $this->validateEmail($email)) {
            $output->writeln("<error>\"{$email}\" is not a valid email address.</error>");
            return 1;
        }

        $password = $this->askForPassword($input, $output);
        if (false === $this->validatePassword($password)) {
            $output->writeln('<error>The password needs to be at least 6 characters long.</error>');
            return 1;
        }

        $this->commandBus->dispatch(
            new AddAdministrator(
                new Administrator(Uuid::uuid4(), new Email($email), $password)
            )
        );

        $output->writeln("<info>Successfuly created an administrator for email \"{$email}\"</info>");
        return 0;
    }

    private function askForPassword(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please provide a password: </info>');
        $question->setHidden(true);

        return $helper->ask($input, $output, $question);
    }

    private function validateEmail($email): bool
    {
        return false !== filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validatePassword($password): bool
    {
        if (false === is_string($password)) {
            return false;
        }

        if (6 > mb_strlen($password)) {
            return false;
        }

        // @TODO - more validation
        return true;
    }
}
