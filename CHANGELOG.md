# Changelog

## 0.0.1

The first release of the PHP package. It covers the whole TypeScript SDK as it stood when this
release was cut, provider imports from SendGrid, Postmark, Mailgun and Mailchimp included: 430
methods in 41 namespaces, each under its TypeScript name. A parity check in the package's own build
proves that every one of them sends exactly the request its TypeScript twin sends when given the
same arguments, once with only the required arguments and once with every option set, retries the
same way and returns the same shape.

### Added

`OpenEmail\OpenEmail` has a property for every namespace, from `emails`, `threads` and `templates`
to `providerImports`, `dnsConnections` and `account`, and the methods on each keep their TypeScript
names. A request body is an associative array whose keys keep the API's camelCase names, the options
of a call are named arguments (`idempotencyKey:`, `limit:`, `cursor:`, `apiKey:`), and a response
comes back as the decoded associative array.

    $client = new OpenEmail();
    $client->emails->send(['from' => 'billing@acme.com', 'to' => 'ada@example.com', 'subject' => 'Invoice', 'text' => 'Attached.']);

Every paginated resource has `list`, which returns one `OpenEmail\Result\Page` with `items`,
`hasMore` and `nextCursor`, `listAll`, which returns every item as an array, and `iterate`, which
returns a Generator that fetches the next page only when it gets there. `PeoplePage`,
`TempMessagesPage`, `AddressBookPage`, `AddressBook`, `BatchResult` and `TemplateSends` carry the
answers that hold more than a list. Each is immutable, `IteratorAggregate` and `Countable`.

An API refusal throws `OpenEmail\Exception\ApiException` or its subclass for the error type:
`InvalidRequestException`, `AuthenticationException`, `PermissionException`, `NotFoundException`,
`ConflictException`, `ValidationException` or `RateLimitException`. Each carries `status`, `type`,
`errorCode`, `param`, `docUrl`, `requestId`, `retryAfterSeconds`, `fields` and `body`, and answers
`isAuth()`, `isPermission()`, `isScopeMissing()`, `isStepUpRequired()`, `isInvalidRequest()`,
`isValidation()`, `isNotFound()`, `isConflict()`, `isRateLimited()`, `isServerError()` and
`isRetryable()`. No response at all throws `NetworkException`, with `isTimeout()` when the deadline
passed, and a mistake in the call itself throws `InvalidArgumentException` before anything is sent.
Every exception the package throws implements `OpenEmail\Exception\OpenEmailException`.

`new OpenEmail()` and `OpenEmail::createClient()` read `OPENEMAIL_API_KEY`,
`OPENEMAIL_ACCESS_TOKEN` and `OPENEMAIL_BASE_URL` for anything you leave out, from `getenv()`,
`$_SERVER` or `$_ENV`. `OpenEmail::init()`, `OpenEmail::getClient()` and `OpenEmail::resetClient()`
manage one shared client. `OpenEmail::createTempMail()` opens disposable inboxes with no credential.

`accessToken:` takes an OAuth access token or a callable that returns one, called before every
request, so a token renews without rebuilding the client. Every method takes `apiKey:` to act with
another key for one call, and the `tempMail` methods take `inboxToken:`. Every parameter that
carries a credential is marked `#[\SensitiveParameter]`, and a client keeps each credential in a
`\SensitiveParameterValue`, so no dump, export or cast of a client shows one. `var_dump()`,
`print_r()` and `json_encode()` of a request show `[redacted]` in place of its Authorization header.

`OpenEmail::verifyWebhookSignature()` checks a delivery's HMAC in constant time, refuses one more
than five minutes old and returns the decoded event. It reads the signature from an array of headers
in any case, from `$_SERVER`, from a Symfony or Laravel header bag, or from a PSR-7 message.

`OpenEmail::toBase64()`, `isApiKey()`, `isAccessToken()`, `isSealed()`, `resolveLanguage()`,
`languageByCode()` and `isRtlLanguage()` are the helpers of the TypeScript SDK, and every value set
its package root exports is a class in `OpenEmail\Constants`, such as `WebhookEvents` and
`ApiScopes`, with a `values()` method for the whole set.

### The transport

The package needs PHP 8.2 or later with the curl and json extensions, and nothing else. Requests go
through `OpenEmail\Http\CurlHttpClient`, one cURL handle per client that keeps its connection open
between requests and opens a new one after `pcntl_fork`, over TLS 1.2 or later with redirects never
followed. `$client->close()` drops that connection, which a worker calls before it forks so that no
child can close the parent's. It takes `caBundle:`, `proxy:` and `curlOptions:`, and cURL honours
`HTTPS_PROXY` and `NO_PROXY`. `OpenEmail\Http\Psr18HttpClient` sends the requests through Guzzle,
Symfony HttpClient or any other PSR-18 client instead, finding PSR-17 factories on its own, and
anything that implements `OpenEmail\Http\HttpClient` can take the place of either, which is how a
test runs without a network.

`timeout:` bounds a whole attempt in seconds, 0 turns it off, and uploads wait at least ten minutes.
Reads, sends and every write that is safe to repeat are retried on 408, 500, 502, 503 and 504 with
backoff, and on a 429 only when its `Retry-After` is a minute or less. Every send carries an
idempotency key that its retries reuse, so a retry never sends a second message.

The client refuses to send a credential over plain `http:` to anything but this machine, refuses a
base URL on `0.0.0.0`, refuses a method, header name or header value that would split a request, and
never lets `raw->request()` leave the base URL's origin. It trims header values as `fetch` does, so
a key read from a file that ends in a newline still works. Request bodies are encoded as the
TypeScript SDK encodes them, numbers included, and `DateTimeInterface` values become UTC instants.
An iterable such as a Laravel collection is sent as the list or map it holds, an array whose integer
keys have gaps is sent as a list, and a body that contains itself is refused rather than crashing
PHP. An attachment's bytes may be given as a stream, an `SplFileInfo` or a PSR-7 stream. Responses
are decoded the way a browser would, so a byte order mark, invalid UTF-8 or a lone surrogate never
breaks a read.

When a newer version is on Packagist, the client says so once on the command line, only on a
terminal. `disableUpdateNotice: true` or `OPENEMAIL_DISABLE_UPDATE_NOTICE=1` turns that off.
