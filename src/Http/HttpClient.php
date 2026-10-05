<?php

declare(strict_types=1);

namespace OpenEmail\Http;

interface HttpClient
{
    public function send(HttpRequest $request): HttpResponse;
}
