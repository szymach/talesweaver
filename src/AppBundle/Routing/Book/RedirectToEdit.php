<?php

namespace AppBundle\Routing\Book;

use AppBundle\Book\Created\Event;
use AppBundle\Event\Recorder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectToEdit
{
    /**
     * @var Recorder
     */
    private $recorder;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router, Recorder $recorder)
    {
        $this->router = $router;
        $this->recorder = $recorder;
    }

    public function createResponse(): RedirectResponse
    {
        $filter = function($event) {
            return $event instanceof Event;
        };
        $reducer = function ($initial, Event $event) {
            return $event->getId();
        };

        return new RedirectResponse(
            $this->router->generate(
                'app_book_edit',
                [
                    'id' => array_reduce(
                        array_filter($this->recorder->recordedMessages(), $filter),
                        $reducer
                    )
                ]
            )
        );
    }
}
