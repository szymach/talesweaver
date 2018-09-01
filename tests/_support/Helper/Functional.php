<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Helper;

use Codeception\Module;
use Codeception\Module\Symfony;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Repository\Doctrine\AuthorRepository;
use Talesweaver\Tests\FunctionalTester;

class Functional extends Module
{
    public function getSymfony(): Symfony
    {
        return $this->getModule('Symfony');
    }

    /**
     * phpcs:disable
     */
    public function _beforeSuite($settings = [])
    {
        $this->getSymfony()->_getContainer();
        $this->getTranslatableListener()->setLocale('pl');
        $this->clearAuthors();
    }

    /**
     * phpcs:disable
     */
    public function _afterSuite()
    {
        $this->clearAuthors();
    }

    private function getTranslatableListener(): TranslatableListener
    {
        return $this->getSymfony()->grabService('test.fsi_doctrine_extensions.listener.translatable');
    }

    private function clearAuthors(): void
    {
        /* @var $manager EntityManagerInterface */
        $manager = $this->getSymfony()->grabService('doctrine.orm.entity_manager');
        /* @var $authorRepository AuthorRepository */
        $authorRepository = $manager->getRepository(Author::class);
        $author = $authorRepository->findOneByEmail(new Email(FunctionalTester::AUTHOR_EMAIL));
        if (null === $author) {
            return;
        }

        $manager->remove($author);
        $manager->flush();
    }
}
