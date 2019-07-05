<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\Module\REST;
use Codeception\Module\Symfony;
use Codeception\TestInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class FormModule extends Module
{
    public const ERROR_SELECTOR = '* .form-error-message';
    public const LOCALE = 'pl';

    /**
     * @var Symfony
     */
    private $symfony;

    /**
     * @var REST
     */
    private $rest;

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

    public function grabCsrfTokenFor(string $tokenId): string
    {
        /** @var CsrfTokenManagerInterface $manager */
        $manager = $this->symfony->grabService('security.csrf.token_manager');

        return $manager->getToken($tokenId)->getValue();
    }

    public function fetchTokenFromAjaxResponse(string $fieldId): string
    {
        $response = json_decode($this->rest->grabResponse(), true);
        $this->assertArrayHasKey('form', $response);

        $crawler = new Crawler($response['form']);
        return $crawler->filter($fieldId)->attr('value');
    }

    /**
     * phpcs:disable
     */
    public function _before(TestInterface $test)
    {
        $this->symfony = $this->getModule('Symfony');
        $this->rest = $this->getModule('REST');
    }

    private function getFormFactory(): FormFactoryInterface
    {
        return $this->symfony->grabService('form.factory');
    }
}
