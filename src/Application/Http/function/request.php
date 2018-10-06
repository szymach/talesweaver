<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;

function is_xml_http_request(ServerRequestInterface $request): bool
{
    return in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true);
}
