<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\Module\Symfony;
use Codeception\TestInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormModule extends Module
{
    public const ERROR_SELECTOR = '* .form-error-message';
    public const LOCALE = 'pl';

    /**
     * @var Symfony
     */
    private $symfony;

    public function createTooLongString(): string
    {
        return bin2hex(random_bytes(128));
    }

    public function createForm(string $class, $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create($class, $data, array_merge(
            ['csrf_protection' => false],
            $options
        ));
    }

    public function getRequest(array $postData): Request
    {
        $request = new Request([], $postData);
        $request->setMethod(Request::METHOD_POST);
        $request->setLocale(self::LOCALE);
        $request->setDefaultLocale(self::LOCALE);

        return $request;
    }

    public function seeNumberOfErrors(int $count, string $selector = self::ERROR_SELECTOR): void
    {
        $this->symfony->seeNumberOfElements($selector, $count);
    }

    public function seeError(string $content, string $field): void
    {
        $this->symfony->see($content, sprintf(
            'input[name="%s"] + %s, select[name="%s"] + %s',
            $field,
            self::ERROR_SELECTOR,
            $field,
            self::ERROR_SELECTOR
        ));
    }

    public function seeErrorAlert(string $content): void
    {
        $this->symfony->see($content, '.alert-danger.alert-form');
    }

    /**
     * phpcs:disable
     */
    public function _before(TestInterface $test)
    {
        $this->symfony = $this->getModule('Symfony');
    }

    private function getFormFactory(): FormFactoryInterface
    {
        return $this->symfony->grabService('form.factory');
    }
}
