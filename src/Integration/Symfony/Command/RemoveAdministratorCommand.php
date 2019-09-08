<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Security\RemoveAdministrator;
use Talesweaver\Application\Query\Security\AdministratorByEmail;
use Talesweaver\Domain\Administrator;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Symfony\Bus\QueryBus;
use function filter_var;

final class RemoveAdministratorCommand extends Command
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(QueryBus $queryBus, CommandBus $commandBus)
    {
        parent::__construct();
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email of the administrator to remove.')
            ->setDescription('This command can remove an administrator.')
            ->setName('talesweaver:admin:remove')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        if (false === $this->validateEmail($email)) {
            $output->writeln("<error>\"{$email}\" is not a valid email address.</error>");
            return 1;
        }

        /** @var Administrator|null $administrator */
        $administrator = $this->queryBus->query(new AdministratorByEmail(new Email($email)));
        if (false === $administrator instanceof Administrator) {
            $output->writeln("<error>No administrator for email \"{$email}\".</error>");
            return 1;
        }

        $this->commandBus->dispatch(new RemoveAdministrator($administrator));

        $output->writeln("<info>Administrator \"{$email}\" has been removed.</info>");
        return 0;
    }

    private function validateEmail($email): bool
    {
        return false !== filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
