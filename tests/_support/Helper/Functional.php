<?php
namespace Helper;

use Codeception\Module;
use Codeception\Module\Symfony;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;

class Functional extends Module
{
    public function _beforeSuite($settings = [])
    {
        $this->getTranslatableListener()->setLocale('pl');
    }

    private function getTranslatableListener() : TranslatableListener
    {
        return $this->getSymfony()->_getContainer()->get('fsi_doctrine_extensions.listener.translatable');
    }

    private function getSymfony() : Symfony
    {
        return $this->getModule('Symfony');
    }
}
