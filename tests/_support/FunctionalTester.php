<?php

declare(strict_types=1);

namespace Talesweaver\Tests;

use Codeception\Actor;
use Codeception\Lib\Friend;
use Doctrine\Common\Persistence\ObjectManager;
use Talesweaver\Tests\_generated\FunctionalTesterActions;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
final class FunctionalTester extends Actor
{
    use FunctionalTesterActions;

    public function canSeeAlert(string $content, string $type = 'success'): void
    {
        $this->canSee($content, sprintf('.alert.alert-%s', $type));
    }

    public function refreshEntities(array $objects): void
    {
        /** @var ObjectManager $manager */
        $manager = $this->grabService('doctrine.orm.entity_manager');
        array_walk($objects, function (object $object) use ($manager): void {
            $manager->refresh($object);
        });
    }
}
