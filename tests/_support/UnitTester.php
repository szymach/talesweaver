<?php

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

    /**
     * @var User
     */
    private $user;

    public function createForm($class, $data = null)
    {
        return $this->getFormFactory()->create($class, $data, ['csrf_protection' => false]);
    }

    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->grabService('form.factory');
    }

    /**
     * @param array $postData
     * @return Request
     */
    public function getRequest(array $postData)
    {
        $request = new Request([], $postData);
        $request->setMethod(Request::METHOD_POST);
        return $request;
    }

    public function getUser(): User
    {
        if (!$this->user) {
            $this->user = new User('test@example.com', 'password', [new UserRole('ROLE_USER')]);
        }

        return $this->user;
    }
}
