<?php

namespace AppBundle\Behat\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

abstract class AbstractPage extends Page
{
    /**
     * @param array $urlParameters
     * @return boolean
     */
    public function isOpen(array $urlParameters = [])
    {
        if (!isset($urlParameters['id'])) {
            $urlParameters['id'] = '*';
        }
        return parent::isOpen($urlParameters);
    }

    /**
     * @param array $urlParameters
     */
    protected function verifyUrl(array $urlParameters = [])
    {
        $currentUrl = $this->getCurrentUrl();
        if (!preg_match($this->getUrlRegexp($urlParameters), $currentUrl)) {
            throw new UnexpectedPageException(sprintf(
                'Expected to be on "%s" but found "%s" instead',
                $this->getUrlRegexp($urlParameters),
                $currentUrl
            ));
        }
    }

    /**
     * @param array $urlParameters
     *
     * @return string
     */
    protected function getUrlRegexp(array $urlParameters = [])
    {
        $url = $this->getPath();
        foreach ($urlParameters as $parameter => $value) {
            if ($value === '*') {
                $url = str_replace(sprintf('{%s}', $parameter), '[^/?&]+', $url);
            } else {
                $url = str_replace(sprintf('{%s}', $parameter), $value, $url);
            }
        }
        $url = str_replace('?', '\\?', $url);
        $baseUrl = rtrim($this->getParameter('base_url'), '/') . '/';
        return '#' . (0 !== strpos($url, 'http') ? $baseUrl . ltrim($url, '/') : $url) . '#';
    }

    /**
     * @return string
     */
    private function getCurrentUrl()
    {
        $currentUrlParts = parse_url($this->getSession()->getCurrentUrl());
        if (isset($currentUrlParts['query'])) {
            parse_str($currentUrlParts['query'], $currentUrlParts['query']);
            unset($currentUrlParts['query']['redirect_uri']);
            if (!empty($currentUrlParts['query'])) {
                $currentUrlParts['query'] = http_build_query($currentUrlParts['query']);
            } else {
                unset($currentUrlParts['query']);
            }
        }
        return sprintf(
            '%s://%s%s%s%s',
            $currentUrlParts['scheme'],
            $currentUrlParts['host'],
            isset($currentUrlParts['port']) ? (':' . $currentUrlParts['port']) : '',
            $currentUrlParts['path'],
            isset($currentUrlParts['query']) ? ('?' . $currentUrlParts['query']) : ''
        );
    }
}
