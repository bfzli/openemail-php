<div align='center'>
   <a href='https://openemail.uk'>
        <img
            src='https://openemail.uk/logo.svg'
            alt='OpenEmail Logo'
            width='180'
        />
   </a>

   <br />
</div>

<p align='center'>
    Email you can build on. Send mail, read the mailbox and automate a workspace from PHP.
</p>

<p align='center'>
    <a href='https://openemail.uk'><b>Website</b></a>
    •
    <a href='https://openemail.uk/docs/php'><b>Documentation</b></a>
    •
    <a href='https://openemail.uk/docs/php/reference/methods'><b>Every method</b></a>
    •
    <a href='https://openemail.uk/docs/api/reference'><b>API Reference</b></a>
</p>

<br />

## The PHP SDK

The official PHP client for the OpenEmail API. It has one method for every method of the TypeScript SDK, under the same names, and a parity check proves that each one sends the same request. It needs PHP 8.2 or later with the curl and json extensions, and nothing else: requests go through one cURL handle per client, which keeps its connection open between requests. Any PSR-18 client, such as Guzzle or Symfony HttpClient, can take its place.

It carries a workspace API key or an OAuth access token, so it belongs on a server, in a job or in a tool that runs on your own machine.

### Installing
```bash
composer require openemail/sdk
```

### Using
```php
use OpenEmail\OpenEmail;

$client = new OpenEmail();

$sent = $client->emails->send([
    'from' => 'Acme Billing <billing@acme.com>',
    'to' => 'ada@example.com',
    'subject' => 'Your September invoice',
    'html' => '<p>Your invoice is attached.</p>',
    'attachments' => [[
        'filename' => 'invoice.pdf',
        'content' => OpenEmail::toBase64((string) file_get_contents('invoice.pdf')),
        'contentType' => 'application/pdf',
    ]],
]);

echo $sent['id'], ' ', $sent['status'], PHP_EOL;
```

`new OpenEmail()` reads the key from `OPENEMAIL_API_KEY`. Create one in OpenEmail under Settings, API keys. It is shown once, and it belongs in an environment variable or your secrets store rather than in code. Pass `apiKey:` when your configuration lives somewhere else.

A request body is an associative array whose keys keep the API's names, so `replyTo`, `scheduledAt` and `contentType` stay camelCase, and so do the named arguments of the call itself: `idempotencyKey:`, `apiKey:`, `limit:`, `cursor:`. What comes back is the decoded JSON as an associative array, read with `$sent['id']`.

Or configure a shared client once and reach it from anywhere:

```php
use OpenEmail\OpenEmail;

OpenEmail::init(timeout: 15);

OpenEmail::getClient()->emails->send([
    'from' => 'hello@acme.com',
    'to' => 'ada@example.com',
    'subject' => 'Welcome',
    'text' => 'Glad you are here.',
]);
```

Every send carries an idempotency key, generated once per call and reused by its retries, so a retried request replays the original message rather than sending a second one. Pass your own with `idempotencyKey:` to make that hold across processes and restarts.

### Reading the mailbox
```php
$page = $client->threads->list(folder: 'inbox', limit: 25);

foreach ($page as $thread) {
    $full = $client->threads->get($thread['id']);

    echo $thread['id'], ' ', $full['messageCount'], ' ', $full['hasUnread'] ? 'unread' : 'read', PHP_EOL;
}

foreach ($client->threads->iterate(folder: 'inbox', query: 'invoice') as $thread) {
    echo $thread['id'], PHP_EOL;
}
```

A row of a thread list carries the thread's id, and `threads->get` returns the thread with its messages.

Every paginated resource has `list` for one page (an `OpenEmail\Result\Page` with `items`, `hasMore` and `nextCursor`, which `foreach` walks), `listAll` for every page at once as an array, and `iterate`, which returns a Generator that fetches the next page only when it gets there, so a `break` stops early. `addresses->listAll` is the exception and returns the whole address book.

### Errors
```php
use OpenEmail\Exception\ValidationException;

try {
    $client->templates->send('order-shipped', [
        'from' => 'dispatch@acme.com',
        'to' => 'ada@example.com',
        'props' => ['orderId' => 'AC-4192'],
    ]);
} catch (ValidationException $error) {
    error_log($error->errorCode . ' ' . $error->param . ' ' . $error->requestId);

    throw $error;
}
```

An API refusal throws an `OpenEmail\Exception\ApiException`, or the subclass for its type: `AuthenticationException`, `PermissionException`, `NotFoundException`, `ConflictException`, `ValidationException`, `RateLimitException` or `InvalidRequestException`. Each carries `status`, `type`, `errorCode`, `param`, `requestId` and `body`, plus `isValidation()`, `isNotFound()`, `isRateLimited()` and friends to branch on. No response at all throws `NetworkException`, with `isTimeout()` when the deadline passed. A mistake in the call itself, such as a malformed key or an empty id, throws `InvalidArgumentException` before anything is sent. Every one of them implements `OpenEmail\Exception\OpenEmailException`.

### Webhooks
```php
use OpenEmail\Exception\WebhookSignatureException;
use OpenEmail\OpenEmail;

try {
    $event = OpenEmail::verifyWebhookSignature(
        (string) file_get_contents('php://input'),
        $_SERVER,
        (string) getenv('OPENEMAIL_WEBHOOK_SECRET'),
    );
} catch (WebhookSignatureException) {
    http_response_code(400);

    return;
}

error_log($event['type'] . ' ' . $event['id']);
http_response_code(204);
```

It checks the HMAC in constant time and rejects a delivery more than five minutes old, then returns the decoded event. Pass the raw body: a parsed or re-encoded body no longer matches its signature. The headers can be an array in any case, `$_SERVER`, a Symfony or Laravel header bag, or a PSR-7 request.

### Disposable inboxes
```php
use OpenEmail\OpenEmail;

$tempMail = OpenEmail::createTempMail();

$inbox = $tempMail->create(['ttlMinutes' => 60]);

$page = $tempMail->listMessages($inbox['id'], inboxToken: $inbox['token']);
```

`create` needs no credential and is the only call that returns the inbox token, so keep it.

### OAuth access tokens
An app a person connected to OpenEmail with OAuth, such as a command line tool or an agent, holds an access token rather than an API key. Pass it as `accessToken:`, either the token itself or a callable that returns it:

```php
use OpenEmail\OpenEmail;

$tokens = ['current' => 'token-from-your-oauth-flow'];

$client = new OpenEmail(accessToken: fn(): string => $tokens['current']);
```

The callable runs before every request, so renew the token there when it is close to expiring and the client never has to be rebuilt. Pass `apiKey:` or `accessToken:`, not both. A client reads `OPENEMAIL_ACCESS_TOKEN` when you pass neither and `OPENEMAIL_API_KEY` is not set.

A token acts for a person, so before a sensitive change, such as deleting a domain or changing a webhook, it is asked for the same verification code the web app asks for. The request throws with `isStepUpRequired()`. Ask for a code, check it, then make the request again:

```php
use OpenEmail\Exception\ApiException;

$domainId = 'b3e1f0a4-6c2d-4e8a-9f17-2d5c8a0b4e6f';

try {
    $client->domains->delete($domainId);
} catch (ApiException $error) {
    if (!$error->isStepUpRequired()) {
        throw $error;
    }

    $challenge = $client->security->beginStepUp();
    echo $challenge['method'] === 'email' ? 'Enter the code we emailed to ' . $challenge['sentTo'] : 'Enter the code from your authenticator app', PHP_EOL;

    $client->security->verifyStepUp(['code' => trim((string) fgets(STDIN))]);
    $client->domains->delete($domainId);
}
```

API keys are never asked for a code.

### Configuring
```php
use OpenEmail\OpenEmail;

$client = new OpenEmail(
    apiKey: (string) getenv('OPENEMAIL_API_KEY'),
    baseUrl: 'https://api.openemail.uk',
    timeout: 30,
    maxRetries: 2,
    headers: ['X-Team' => 'billing'],
);
```

Anything you leave out is read from the environment: `OPENEMAIL_API_KEY`, then `OPENEMAIL_ACCESS_TOKEN`, and `OPENEMAIL_BASE_URL`, where a bare host such as `localhost:2222` gets its scheme added. `OpenEmail::createClient(...)` and `OpenEmail::init(...)` take the same named arguments.

Use an `https:` origin: the client refuses to send an API key, an access token or an inbox token over plain `http:` unless the server is on this machine, at `localhost`, a `127.x.x.x` address or `::1`. A base URL on `0.0.0.0` is refused when the client is built, since that is the address a server listens on: use `127.0.0.1` with the same port. `timeout:` is in seconds and bounds a whole attempt, 0 turns it off, and uploads wait at least ten minutes. Reads, sends and every write that is safe to repeat are retried on 408, 500, 502, 503 and 504 with backoff, and on a 429 only when it carries a `Retry-After` of a minute or less. Any other write is never retried. Every method takes `apiKey:` to act with another key for one call, so one process can serve several workspaces with one client, and the `tempMail` methods take `inboxToken:` instead. cURL honours `HTTPS_PROXY` and `NO_PROXY`.

An endpoint no method wraps yet is one `$client->raw->request()` away, with the client's credential, base URL, timeout and retry policy applied:

```php
$label = $client->raw->request('/labels', method: 'POST', body: ['name' => 'Invoices']);
```

The path must begin with a single `/`, and a path whose finished URL leaves the base URL's origin throws before a request is sent, so the credential never reaches another host.

`httpClient:` swaps the HTTP layer. Wrap any PSR-18 client in `OpenEmail\Http\Psr18HttpClient`, or implement `OpenEmail\Http\HttpClient`, one method that takes an `HttpRequest` and returns an `HttpResponse`, which is how a test drives the client without a network.

When a newer version is on Packagist the client says so once, on the command line and only on a terminal. `OPENEMAIL_DISABLE_UPDATE_NOTICE=1` or `disableUpdateNotice: true` turns that off.

Everything else, from templates, rules, tracking, calendar, contacts, audiences and broadcasts to roles, members, settings, mailbox imports and provider imports, plus Laravel and Symfony recipes and the full reference for every method, lives in the [documentation](https://openemail.uk/docs/php). What changed in each release is in the [changelog](https://openemail.uk/docs/php/changelog).
