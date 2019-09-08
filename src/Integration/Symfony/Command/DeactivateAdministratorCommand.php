<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Security\DeactivateAdministrator;
use Talesweaver\Application\Query\Security\AdministratorByEmail;
use Talesweaver\Domain\Administrator;
use Talesweaver\Domain\ValueObject\Email;
use function filter_var;

final class DeactivateAdministratorCommand extends Command
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
        $this->addArgument('email', InputArgument::REQUIRED, 'Email of the administrator to deacticate.')
            ->setDescription('This command can deactivate an administrator.')
            ->setName('talesweaver:admin:deactivate')
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

        if (false === $administrator->isActive()) {
            $output->writeln("<error>Administrator \"{$email}\" is already inactive.</error>");
            return 1;
        }

        $this->commandBus->dispatch(new DeactivateAdministrator($administrator));

        $output->writeln("<info>Administrator \"{$email}\" has been deactivated.</info>");
        return 0;
    }

    private function validateEmail($email): bool
    {
        return false !== filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
