<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Repository\Doctrine\UserRepository;
use App\Tests\FunctionalTester;
use Codeception\Module;
use Codeception\Module\Symfony;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Entity\User;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;

class Functional extends Module
{
    /**
     * phpcs:disable
     */
    public function _beforeSuite($settings = [])
    {
        $this->getSymfony()->_getContainer();
        $this->getTranslatableListener()->setLocale('pl');
        $this->clearUser();
    }

    /**
     * phpcs:disable
     */
    public function _afterSuite()
    {
        $this->clearUser();
    }

    private function getTranslatableListener(): TranslatableListener
    {
        return $this->getSymfony()->grabService('test.fsi_doctrine_extensions.listener.translatable');
    }

    private function getSymfony(): Symfony
    {
        return $this->getModule('Symfony');
    }

    private function clearUser(): void
    {
        /* @var $manager EntityManagerInterface */
        $manager = $this->getSymfony()->grabService('doctrine.orm.entity_manager');
        /* @var $userRepository UserRepository */
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => FunctionalTester::USER_EMAIL]);
        if (!$user) {
            return;
        }

        $manager->remove($user);
        $manager->flush();
    }
}
