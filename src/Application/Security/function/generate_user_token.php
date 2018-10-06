<?php

declare(strict_types=1);

function generate_user_token(): string
{
    return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
}
