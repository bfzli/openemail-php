# Changelog

## 0.0.2

### Added

`contacts` reads and writes the address book as contact cards. `listCards()`, `listAllCards()` and
`iterateCards()` page through every card, newest first, with `email` to find the card of one saved
contact and `withoutEmail` for the cards that have no address. `getCard()`, `createCard()`,
`updateCard()` and `deleteCard()` manage one card: phone numbers, other email addresses, postal
addresses, websites, organisation, department, job title, birthday, nickname and the name in parts.
A card with an email address is a saved contact, and deleting the card deletes the contact. Phones
and computers that sync the address book over CardDAV get every change on their next sync. Reading
needs `contacts:read` and every change `contacts:write`. New constant class: `ContactCardLabels`.

    $card = $client->contacts->createCard([
        'name' => 'Plumber',
        'phones' => [['value' => '+44 7700 900123', 'label' => ContactCardLabels::MOBILE]],
    ]);
    echo $card['id'], PHP_EOL;

`calendar` connects the calendar to other calendar apps. `listFeeds()`, `createFeed()`,
`resetFeed()` and `deleteFeed()` manage secret links that publish the calendar as an iCalendar feed
for Google Calendar, Outlook or Apple Calendar, with every detail (`full`) or busy times only
(`busy`). `listSubscriptions()`, `createSubscription()`, `updateSubscription()`,
`refreshSubscription()` and `deleteSubscription()` manage the other calendars you subscribe to by
link, whose events show read-only. `respondToEvent()` takes `proposedStart` and `proposedEnd` to
propose a new time with an answer, every attendee carries the time they proposed, and
`declineProposal()` keeps the original time and tells the guest. Reading needs `calendar:read` and
every change `calendar:write`, `declineProposal()` also needs `emails:send`, and a key limited to
particular addresses or domains cannot manage links or subscriptions. New constant classes:
`CalendarFeedModes`, `CalendarColors`, `CalendarSubscriptionStatuses` and
`CalendarSubscriptionErrors`.

    $feed = $client->calendar->createFeed(['name' => 'Work (busy)', 'mode' => CalendarFeedModes::BUSY]);
    echo $feed['url'], PHP_EOL;

    $client->calendar->createSubscription(['url' => 'webcal://example.com/holidays.ics', 'color' => CalendarColors::GREEN]);

Every event carries `subscriptionId`, the calendar subscription it comes from or `null` for your own
calendar, `rdates`, the occurrences added outside its rule, and `recurrenceId`, which on one changed
occurrence of a repeating event is the start it replaces, and every occurrence carries
`subscriptionId` too.

`domains->updateMailAppSettings()` turns each kind of connection an address accepts on or off:
`imap`, `smtp` and `pop3` for mail, `caldav` for its calendar and `carddav` for contacts. A
switched-off kind refuses every sign-in, and apps already connected over it are signed out within
minutes. It needs `domains:write`, and an access token acting for a member also needs a role that
manages domains. `domains->updateAppPassword()` renames an app password and needs
`members:write`. The mail app settings carry `protocols`, the switches as they stand, and
`carddav`, the server that puts the contacts of the workspace into Apple Contacts or any other
CardDAV app, which is left out of the settings a setup link hands over. `caldav` and `carddav`
carry `principalUrl` for apps that ask for the account address on the server. A created app
password carries `appleProfile`, an Apple configuration profile with the password inside that sets
up an iPhone, iPad or Mac in one step, and `MailAppProtocols` gains `CARDDAV`.

    $client->domains->updateMailAppSettings($domainId, $addressId, ['pop3' => false]);

    $created = $client->domains->createAppPassword($domainId, $addressId, ['name' => 'iPhone']);
    file_put_contents('profile.mobileconfig', $created['appleProfile']);

The mail app settings carry `caldav`, the server that puts the calendar of an address into Apple
Calendar, Thunderbird, DAVx5 on Android or any other CalDAV app, with its `url`, `host`, `port` and
`security`, and `MailAppProtocols` gains `CALDAV` for an app password a calendar app last used.

`domains->update()` takes `senderName` in its patch, the name mail from a domain goes out under
when the sending address has no name of its own, such as `Acme Support`. Mail sent without a name
in `from` goes out under the name of the address, then the sender name of its domain, then the name
of the workspace. It is trimmed, at most 120 characters, and null or an empty string removes it. It
applies to the whole domain, so only a key that holds the whole domain may change it. Every domain
array carries `senderName`, null when none is set.

    $client->domains->update($domainId, ['senderName' => 'Acme Support']);

`domains->getMailAppSettings()`, `listAppPasswords()`, `createAppPassword()`,
`deleteAppPassword()` and `sendMailAppSetup()` put an address into Apple Mail, Gmail, Outlook,
Thunderbird or any other mail app over IMAP, SMTP and POP3. `getMailAppSettings()` reads the
servers and the username to sign in with. `createAppPassword()` makes a password a mail app signs
in with, acting as the caller for that address alone, and returns it this once with the settings.
`listAppPasswords()` lists the app passwords of the address with when and how each was last used,
and `deleteAppPassword()` revokes one. `sendMailAppSetup()` emails somebody a link, good once for
three days, that makes an app password and shows it with the settings. Reading the settings needs
`domains:read` and the rest `members:write`, a key limited to particular addresses or domains
cannot manage app passwords, and an OAuth access token needs a verification code to create one or
send a link. The values are in `OpenEmail\Constants\MailAppSecurities`, `MailAppProtocols` and
`AppPasswordOrigins`.

    $created = $client->domains->createAppPassword($domainId, $addressId, ['name' => 'Front desk iPad']);
    echo $created['servers']['username'], ' ', $created['password'], PHP_EOL;

    $client->domains->sendMailAppSetup($domainId, $addressId, ['recipient' => 'ada@example.com']);

A `knowledge` namespace reaches the workspace knowledge base: the notes, files and web pages the
AI uses when it writes replies, drafts email and answers in the assistant. Each item sits at one
level, its `scope`: an empty string for the whole workspace, `@` and a domain, or one address, and
the AI writing for an address reads the address, then its domain, then the workspace.
`knowledge->list()`, `listAll()` and `iterate()` page through the items, with `scope:`, `level:`,
`kind:`, `status:`, `pinned:` and `q:` to narrow them. `levels()` lists the levels the caller can
see and whether it may change items there, and `usage()` reads what the plan allows. `search()`
finds the passages the AI would use for a question. `createNote()`, `addLink()` and `uploadFile()`
add an item, `get()` reads one with its text, `update()` changes one or moves it to another level,
`refresh()` reads one again and `delete()` removes one. Reading needs the new `knowledge:read`
scope and every change `knowledge:write`. The values are in `OpenEmail\Constants\KnowledgeKinds`,
`KnowledgeLevels`, `KnowledgeStatuses`, `KnowledgeFailures` and `KnowledgeOrigins`.

    $client->knowledge->createNote([
        'scope' => '@acme.com',
        'title' => 'Refunds',
        'body' => 'Refunds are paid within 14 days of the return reaching our warehouse.',
        'pinned' => true,
    ]);

    $result = $client->knowledge->search(['query' => 'How long do refunds take?', 'address' => 'support@acme.com']);

The knowledge base learns and keeps itself current. `knowledge->listSuggestions()`,
`listAllSuggestions()` and `iterateSuggestions()` page through what the AI suggests adding, with
`kind:` and `status:`: `learned` notes drawn from replies the workspace sent, and `question`s
senders asked that nothing in the knowledge base answers. `acceptSuggestion()` saves one as a note,
with any change to its title, text, level or pin, and a question becomes a note once you give its
answer in `body`. `dismissSuggestion()` drops one for good. `listFlags()` lists the open warnings
about two items that say nearly the same thing (`duplicate`) or disagree on a fact (`conflict`),
with `itemId:` to keep one item's, and `dismissFlag()` keeps a pair that is fine as it is.
Connectors keep many pages from one source as link items: `addConnector()` crawls a `site`, reads a
`sitemap`, a `feed` or the articles of a `zendesk` help center, and syncs it again on a schedule,
adding new pages, reading changed ones and removing the items of pages that are gone.
`listConnectors()`, `getConnector()`, `updateConnector()`, `deleteConnector()` and
`syncConnector()` manage them. `draftFromThread()` has the AI draft one note from a conversation
without saving it, for `createNote()` to keep, and `stats()` reads how often the AI found
something, where, and which items it used most. `search()` takes `rerank`, which has the AI order
the passages and answers `reranked`. `createNote()` takes `threadId`, and `addLink()` and
`update()` take `refreshDays` (1, 7, 30 or null) to read a page again on its own. Knowledge items
gain `refreshDays`, `nextRefreshAt`, `connectorId`, `threadId`, `uses`, `lastUsedAt` and `flags`,
`threads->replySuggestions()` answers with `sources`, the knowledge base items the suggestions drew
on, and `settings->update()` takes `knowledgeLearning`, which stops the suggestions, the questions
and the conflict checks when it is false. The values are in
`OpenEmail\Constants\KnowledgeSuggestionKinds`, `KnowledgeSuggestionStatuses`, `KnowledgeFlagKinds`,
`KnowledgeFlagStatuses`, `KnowledgeConnectorKinds`, `KnowledgeConnectorStatuses` and
`KnowledgeRefreshDayChoices`.

    foreach ($client->knowledge->iterateSuggestions(kind: KnowledgeSuggestionKinds::QUESTION) as $question) {
        $client->knowledge->acceptSuggestion($question['id'], ['body' => answerFor($question['title'])]);
    }

    $client->knowledge->addConnector(['kind' => KnowledgeConnectorKinds::SITEMAP, 'url' => 'https://acme.com/sitemap.xml', 'scope' => '@acme.com']);

`emails->compose()` takes `draft`, `withSubject`, `from`, `bcc`, `send`, `timeZone`, `template`,
`templates` and `files` in its body, so one description can write or change a whole email, as the
describe bar of the composer does. `draft` is the body written so far and `prompt` says what to
change in it, `withSubject` set to `true` adds `subject` to the answer, and `from`, the address it
goes out as, has it written as that address. `send`, the send options as they stand, lets `prompt`
change the recipients, the send time, tracking, the signature and the language: the answer's
`send` holds only what changed, in the shape `emails->send()` takes, and `null` takes an option
away. With `send['fromOptions']`, the addresses it may go out as, `prompt` can move it to one of
them, which the answer names in `send['from']`. With `send['encrypt']`, given only when every
recipient can receive encrypted mail, `prompt` can turn end-to-end encryption on or off, which the
answer's `send['encrypt']` reports. With `templates` set to `true`, `prompt` can switch the email
to one of the workspace's published templates, which the answer names in `template`, and
`template` names the template the email uses now, so `prompt` leaves its body alone. With `files`
set to `true`, `prompt` can attach files the workspace holds: the answer lists them in `attach`,
each ready to send as `['fileId' => $file['id']]` in `attachments`, and what matched no file in
`attachMissing`. `templates` needs `templates:read` and `files` needs `files:read`. The answer
carries `sources` when the draft drew on the knowledge base: the pinned notes at the levels of the
sending address and the items whose passages matched the request, each with `id`, `title`, `kind`,
`scope` and `url`. `note` says when part of `prompt` cannot be done this way, such as adding
somebody whose address it was not given.

    $composition = $client->emails->compose([
        'prompt' => 'Thank Ada, ask for the invoice by Friday, send it from billing tomorrow at 9',
        'from' => 'grace@acme.com',
        'to' => ['ada@example.com'],
        'send' => ['fromOptions' => ['billing@acme.com']],
    ]);

    $contract = $client->emails->compose(['prompt' => 'Send Ada the signed contract', 'files' => true]);

    $client->emails->send([
        'from' => 'billing@acme.com',
        'to' => 'ada@example.com',
        'text' => $contract['body'],
        'attachments' => array_map(static fn(array $file): array => ['fileId' => $file['id']], $contract['attach'] ?? []),
    ]);

`threads->list()`, `listAll()` and `iterate()` take `semantic:`. With `semantic: true` the plain
words of `query:` match by meaning instead of spelling, in any language: the threads closest to the
description come back best match first, along with every thread the words match as text.
Operators and the other filters still narrow the search, and `sort:` does not apply.

    $trip = $client->threads->list(folder: 'archive', query: 'flight to Berlin', semantic: true);

Meaning is read from the whole conversation, newest messages first, so an earlier message counts
too. `settings->get()` and `settings->update()` carry `semanticSearch`, the workspace switch for
search by meaning, on unless turned off: `false` stops the workspace's mail going to the embedding
model, deletes what was stored, and makes `semantic: true` run a text search until it is turned back
on.

    $client->settings->update(['semanticSearch' => false]);

The `settings->update()` reference lists the eight privacy fields as keys of `$patch`:
`externalImages`, `trustedSenders`, `blockedSenders`, `blockedDomains`, `blockedWords`,
`useDefaultBlockedWords`, `semanticSearch` and `replySuggestions`.

`threads->counts()` counts drafts and labels too. `folders` has a `draft` row with the drafts
waiting to be sent, whose `unread` is always 0, and the new `labels` list has an array for each of
your labels and each folder it has conversations in, with `id`, `folder`, `count` and `unread`. A
key limited to particular addresses counts only the drafts written from them.

    $labels = $client->threads->counts()['labels'];

The messages `threads->get()` returns carry `sentWith`, what a message was sent with, and
`forwardedFrom`, the address it was first sent to when it reached you through forwarding.
`OpenEmail\Constants\MailServices` holds every value `sentWith` can take.

The messages `threads->get()` returns also carry `verificationCode`, the sign-in or verification
code a message holds, as you would type it. It is set only when OpenEmail is sure: the sender
passed authentication, nothing about the message was flagged, it was not filed to Spam or Trash,
and it holds exactly one code worded as one. Otherwise it is `null`.

`tools->readVerificationCode()` and `tools->estimateCost()` are new, and like the other tools they
need no scope. `readVerificationCode()` finds the sign-in or verification code in a message you
pass in, as `raw` MIME source or as a `subject` with `html` or `text`, with the reader that sets
`verificationCode`. It answers only when it is sure, so `code` is `null` for a message with two
different codes or a number that could be an order, a booking or a promotion. `estimateCost()`
prices every plan for a usage given as `sends:`, `domains:`, `people:`, `aiActions:` and
`interval:`, as the cost calculator on the website does, and names the cheapest plan that fits in
`cheapest`. Amounts are in US cents per month. `OpenEmail\Constants\PlanBlockers` holds the
values of each plan's `blockers`: `domains`, `people` and `sends`.

    $code = $client->tools->readVerificationCode(['raw' => $source])['code'];

    $estimate = $client->tools->estimateCost(sends: 25000, domains: 2, people: 4, aiActions: 30);

`threads->replySuggestions()` reads the replies the reading pane suggests under the latest message
of a thread: up to three, each an array with a `label` of a few words and a `body` ready to send,
written in the language of that message and the voice of the workspace, from the conversation, the
earlier mail with that correspondent, what is known about their organisation and the busy times of
the calendar. They are written once for each new message and kept, so asking again is free until
another message arrives. `state` is `pending` while they are being written and `none` when the
message needs no reply, such as a newsletter or a thread your own reply ended.
`OpenEmail\Constants\ReplySuggestionStates` holds the three states.

    $suggestions = $client->threads->replySuggestions('CAHk7pQ2x9LmZ4-mail.example.com')['suggestions'];

`settings->update()` takes `replySuggestions`, the workspace switch for them, on unless turned off:
`false` stops threads going to a model for suggestions and deletes what was stored, and
`threads->replySuggestions()` then answers `none`.

    $client->settings->update(['replySuggestions' => false]);

`settings->get()` and `settings->update()` reach every setting the app has. The patch takes
`developerMode` besides the account settings and the privacy of the workspace, and the result
returns it. `address` takes `@domain` for a whole domain, besides one address or `*@domain` for a
catch-all. An address or a catch-all can have its own `externalImages`, `trustedSenders` and
blocklist besides its signature and tracking, and `@domain` sets those three for a whole domain.
`null` removes a value the scope sets itself, so it inherits again, and `overrides` in the result
lists what the scope sets. New constant classes: `SettingsTimeFormats`, `SettingsDateFormats`,
`SettingsWeekStarts`, `SettingsColorThemes` and `SettingsImageCompressions`.

    $client->settings->update(['externalImages' => false], address: '@example.com');
    $client->settings->update(['externalImages' => null], address: '@example.com');

`drafts->create()` takes `forwardOf`, the id of a message in `threadId`, to forward it. Sending the
draft with `emails->send(['draftId' => ..., 'to' => ...])` quotes the message below the body and
attaches its files, the way forwarding in the app does, and the subject defaults to the message's
with `Fwd:` in front. `drafts->get()` returns `forwardOf` and each attachment's `size`.

`threads->list()`, `listAll()` and `iterate()` take `address` to keep the threads delivered to one
address, and `tracking->list()`, `listAll()`, `iterate()` and `getStats()` take `address` to keep
what one address sent, the address filters of the app. `templates->publish()` takes
`expectedVersion`, so a draft somebody saved since you reviewed it is refused with 409
`version_conflict` rather than published. The new arguments sit before `limit`, `cursor` and
`apiKey`, so pass the arguments after them by name.

The new constant class `ImportFormats` holds every format a mailbox import reports in `formats`,
`ImportFormats::PROTON` for a Proton Mail export included.

The new constant class `ContactSources` holds `manual`, `auto` and `form`, the last for a contact a
sign-up form added, which `contacts->list()` and `audiences->listContacts()` can filter by.

### Changed

`senders->get()` and `senders->research()` share what is known about a domain with every workspace
and with its subdomains, so `domain` is the organisation's own domain (`stripe.com` for
`billing@mail.stripe.com`), and an answer is looked up again by itself once it is 30 days old.
Personal mailbox providers such as Gmail and the domains of your own workspace are never looked up,
and both methods answer them with a 404, which throws `NotFoundException`. The daily limit of
`senders->research()` counts new domains: a workspace looks up at most 200 a day.

### Fixed

`threads->summary()` no longer documents a cost or a timing that does not happen. A summary spends no
AI actions, and it is written the first time it is asked for and again on the first request after a
newer message, not when mail arrives. While a newer one is written, the previous one comes back as
`ready`.

`drafts->update()` keeps the draft's attachment list, and a draft that forwards a message keeps
forwarding it unless the update moves it to another thread. An update used to empty the list and
drop the forward, so an edited forward went out without the message or its files.

`settings->update()` with `workspaceName` renames the workspace, the way Settings does in the app,
and `settings->get()` returns the workspace's own name. The name used to be saved with your account
settings, which left the workspace as it was.

The method docs say that the rule and template limits answer 422 `workspace_limit_reached`, and that
a walk of webhook deliveries ends when no next cursor comes back or when one repeats, not at an
empty page. The `templates->update()` docs say an archived template refuses to send with 422
`template_archived`, where they said it still sends.

The `security->verifyStepUp()` docs list every call that asks an OAuth access token for a
verification code, 40 in all, and the three that ask only in one case. They named 16.

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
