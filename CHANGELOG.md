# Changelog

## 0.0.3

### Added

A sign-up form field can now fill the contact's card, not only its name. `mapsTo` on a form field
takes five more values, and `OpenEmail\Constants\FormContactFields` holds them: `phone` on a `phone` field, `company` and
`jobTitle` on a `text` field, `website` on a `url` field and `birthday` on a `date` field.
When a sign-up is accepted the answers are saved on the contact card where the card has nothing
yet: a company, job title or birthday already on the card is kept, and a phone number or website
the card already lists is not added twice.

`contacts.get`, and every other call that returns one contact with its audiences, now answers
with `signUps` for a key that also holds `forms:read`: what the address sent through sign-up
forms, newest first and at most 20, each an array with the form, the status and every answer.

`threads->mute()` and `threads->unmute()` mute a conversation and take the mute off, as Mute does in
the app. A muted thread in the inbox moves to the archive, and new mail in it goes straight to the
archive, unread, with no push, sound or desktop notice. Muting is for the whole workspace, a muted
thread carries the built-in `MUTE` label, and `is:muted` finds it in `query:`. Both need
`threads:write`, and the SDK retries them.

    $muted = $client->threads->mute($threadId);
    echo $muted['muted'] ? 'muted' : 'not muted', PHP_EOL;

    $client->threads->unmute($threadId);

The inbox has tabs: Primary, Promotions, Updates, Social and Forums. `threads->list()`, `listAll()`
and `iterate()` take `category:` to read one tab, and `category:promotions` filters the same way in
`query:`. `threads->counts()` returns `categories`, the conversations and unread conversations of
the inbox in each tab. `threads->listSenderCategories()`, `setSenderCategory()` and
`clearSenderCategory()` manage the senders whose mail always goes to one tab, whatever the sorting
rules would say. Saving a choice also moves the inbox threads whose newest message is from that
sender, up to the newest 1,000. Reading needs `threads:read` and every change `threads:write`, and
a key limited to particular addresses or domains can read the choices but not change them. The new
`category:` argument sits before `address:`, `limit:`, `cursor:` and `apiKey:`, so pass the
arguments after it by name. New constant class: `InboxCategories`.

    $offers = $client->threads->list(category: InboxCategories::PROMOTIONS);
    echo count($offers), PHP_EOL;

    $client->threads->setSenderCategory('news@example.com', InboxCategories::PRIMARY);

    foreach ($client->threads->counts()['categories'] as $tab) {
        echo $tab['category'], ': ', $tab['unread'], PHP_EOL;
    }

A `followUps` namespace holds the reminders on mail you sent. `set()` asks for a thread to come back
to the top of the inbox, unread, with the built-in `FOLLOW_UP` label and a push, if nobody replies
by `remindAt`. A real reply closes the reminder by itself, and a thread holds one waiting reminder
per person, so setting another moves the time. `list()` reads the reminders still waiting and the
ones that ended, with `status:`, `threadId:` and `limit:`, and `cancel()` cancels one.
`emails->send()` and each message of `emails->sendBatch()` take `followUpAt` to set a reminder as
the message goes out: a `DateTimeInterface`, an ISO 8601 instant or a duration such as `P3D`, later
than the time the message goes out. Reading needs `threads:read` and every change `threads:write`.
New constant class: `FollowUpStatuses`.

    $client->emails->send([
        'from' => 'ada@example.com',
        'to' => 'grace@example.com',
        'subject' => 'Contract draft',
        'text' => 'The draft is attached. Can you look at it this week?',
        'followUpAt' => 'P3D',
    ]);

    foreach ($client->followUps->list(status: FollowUpStatuses::WAITING) as $reminder) {
        echo $reminder['subject'], ' ', $reminder['remindAt'], PHP_EOL;
    }

An `outOfOffice` namespace manages the automatic reply an address sends while its owner is away.
`set()` saves the reply of one address, or of a catch-all with `*@example.com`, with its message,
subject, start, end and `contactsOnly`, replacing whatever was saved. While it is active, each
person who writes gets the message once for the whole away period, and spam, bulk mail, automatic
mail and bounces get no reply. `list()` reads every reply saved in the workspace, or the reply of
one address with `address:`, and `clear()` removes one. Reading needs `settings:read` and every
change `settings:write`.

    $reply = $client->outOfOffice->set('ada@example.com', [
        'endsAt' => new DateTimeImmutable('2027-01-04T08:00:00Z'),
        'message' => 'I am away until 4 January and will answer when I am back.',
    ]);
    echo $reply['active'] ? 'answering' : 'not answering yet', PHP_EOL;

    $client->outOfOffice->clear('ada@example.com');

Labels can be applied by AI. `labels->create()` and `labels->update()` take `aiInstructions`, what
the label is for in plain words, and `aiEnabled`, which has AI read each arriving message against
them and add the label when it fits. It only labels and never archives or moves mail. Turning it on
needs instructions and a paid plan, and a workspace may have 10 such labels. `labels->test()` runs
the AI over the newest 20 inbox threads and returns the ones the label would catch, without
labelling anything, with the saved instructions or wording you have not saved yet. It needs
`labels:write` and `threads:read`, and spends one AI action. Every label carries `aiInstructions`
and `aiEnabled`.

    $client->labels->update('USER_RECEIPTS', ['aiInstructions' => 'invoices and receipts', 'aiEnabled' => true]);

    $result = $client->labels->test('USER_RECEIPTS');
    echo count($result['matched']), ' of ', $result['examined'], PHP_EOL;

`settings->get()` and `settings->update()` carry three more account settings: `inboxTabs` shows the
inbox as tabs, `primaryOnlyAlerts` keeps pushes, sounds and desktop notifications to mail sorted
into Primary while tabs are on, and `phoneFollowUps` sends a push when a reminder comes due.

    $client->settings->update(['inboxTabs' => true, 'primaryOnlyAlerts' => true]);

Mail travels in two lanes, so a newsletter cannot hold up or harm app mail. `emails->send()`, each
message of `emails->sendBatch()` and `templates->send()` take `stream`: `transactional`, the
default, for mail one person is waiting for, or `broadcast` for newsletters. Every email carries the
`stream` it went out on, and every copy of a broadcast is on the broadcast lane. A new `sending`
namespace reads the lanes: `listStreams()` returns both with their state and their numbers for the
last 7 days, which are recipients, bounces, complaints and the two rates. The broadcast lane pauses
itself above 4% bounces or 0.2% complaints, a send to it is then 409 `stream_paused`, and app mail
keeps going. `resumeStream()` resumes it, for the workspace owner, and asks an app for a
verification code. Reading needs `emails:read` and resuming `domains:write`. New constant classes:
`SendStreams`, `SendingStreamStatuses` and `SendingStreamPauseReasons`.

    use OpenEmail\Constants\SendStreams;

    $client->emails->send([
        'from' => 'news@acme.com',
        'to' => 'ada@example.com',
        'subject' => 'What is new in October',
        'text' => 'Three things shipped this month.',
        'stream' => SendStreams::BROADCAST,
    ]);

    foreach ($client->sending->listStreams()['data'] as $lane) {
        echo $lane['stream'], ' ', $lane['status'], ' ', $lane['window']['bounceRate'], PHP_EOL;
    }

`tools->checkAddress()` says whether an address is worth sending to before the first message:
`valid`, `risky`, `invalid` or `unknown`, with the reasons. It checks the syntax, whether the domain
takes mail, throwaway domains and shared addresses such as `info@`, and suggests a correction for a
likely typo. With `emails:read` it adds what the workspace knows, whether it suppressed the address
and when it last delivered to it or saw it bounce. `deep: true` also asks whether the mailbox
exists, billed per address through pay as you go, and needs `emails:send`. `tools->checkAddresses()`
checks up to 100 in one call. New constant classes: `AddressCheckResults`, `AddressCheckReasons`,
`AddressMxStatuses` and `AddressConfidences`.

    $check = $client->tools->checkAddress('ada@gmial.com');

    if ($check['result'] !== 'valid') {
        echo implode(', ', $check['reasons']), ' ', $check['suggestion'] ?? '', PHP_EOL;
    }

A workspace on Business or Enterprise that sends at least 100,000 emails a month can buy a dedicated
sending IP. `billing->listAddOns()` says whether it is for sale, what it costs, whether the
workspace qualifies and whether it has one. `billing->startAddOnCheckout()` returns the link where
the person pays, and `billing->cancelAddOn()` ends it at the end of the month already paid for.
`sending->listDedicatedIps()` and `sending->getDedicatedIp()` read each IP with its `status` and
`warmupPercent`, since a new IP is warmed up over several weeks. Billing calls need `billing:read`
and `billing:write`, belong to the workspace owner and ask an app for a verification code, and
reading the IPs needs `domains:read`. New constant classes: `AddOns`,
`DedicatedIpIneligibleReasons`, `DedicatedIpStatuses` and `SendingPoolStatuses`.

    use OpenEmail\Constants\AddOns;

    $checkout = $client->billing->startAddOnCheckout(['addOn' => AddOns::DEDICATED_IP]);
    echo 'Pay here: ', $checkout['url'], PHP_EOL;

    foreach ($client->sending->listDedicatedIps()['data'] as $address) {
        echo $address['ip'], ' ', $address['status'], ' ', $address['warmupPercent'], PHP_EOL;
    }

`domains->getMailAppSettings()` returns `smtpSubmission`, the SMTP server on port 587 for apps,
devices and libraries that only offer STARTTLS. Its `security` is `starttls`, a new member of
`MailAppSecurities`, and it takes the same username and app password as `smtp`.

    $settings = $client->domains->getMailAppSettings($domainId, $addressId);
    echo $settings['smtpSubmission']['host'], ':', $settings['smtpSubmission']['port'], PHP_EOL;

`imports` can read a live mailbox, as the Migrations page of the app does. `discoverMailbox()` finds
the mail server for an address and says what the mailbox signs in with. `checkMailbox()` signs in
once and lists the folders and how many messages an import would bring. `connectMailbox()` starts
copying the mailbox into one address, with `contacts` and `calendars` to bring those across too.
The old mailbox is only ever read, a message the address already holds is skipped, and the password
is kept sealed until the import finishes and for 30 days at most. An import waits by itself when
the old provider slows it down: `status` is `parked` and `remote.resumeAt` says when. When the
password stops working it is `needs-password`, and `resume()` sends a new one. They need
`threads:write`, with `contacts:write` and `calendar:write` for those parts. Every import now
carries `source`, `remote` and `children`, and `ImportFormats` gains `imap` and `microsoft`. The
array that holds the password is marked `#[\SensitiveParameter]`, so it stays out of stack traces.
New constant classes: `ImportStatuses`, `MailboxImportSources`, `MailboxProviders`,
`MailboxSignIns`, `MailboxSecurities`, `MailboxSettingsSources`, `MailboxFolderRoles` and
`ImportPauseReasons`.

    $settings = $client->imports->discoverMailbox('ada@gmail.com');

    $check = $client->imports->checkMailbox([
        'email' => 'ada@gmail.com',
        'password' => (string) getenv('OLD_MAILBOX_PASSWORD'),
    ]);
    echo $settings['provider'], ' ', $check['messages'], PHP_EOL;

    $import = $client->imports->connectMailbox([
        'addressId' => 'addr_2b7e',
        'email' => 'ada@gmail.com',
        'password' => (string) getenv('OLD_MAILBOX_PASSWORD'),
        'contacts' => true,
    ]);
    echo $import['status'], ' ', $import['remote']['host'] ?? '', PHP_EOL;

`calendar->importIcs()` and `contacts->importVcf()` take an iCalendar or vCard file as a string of
bytes, a stream resource, an `SplFileInfo` or a PSR-7 stream and queue it. Events become events of
your own and nobody on them is sent anything, and cards become contact cards that join no audience.
What is already here is left as it is and counted, so sending a file twice adds nothing.
`listImports()`, `listAllImports()` and `iterateImports()` read the imports of each kind, newest
first, with the calendars and address books that came with a mailbox import. `getImport()` reads
one with its counts and a report of what it left out and why, and `undoImport()` deletes everything
an import added. Reading needs `calendar:read` or `contacts:read`, and the rest `calendar:write` or
`contacts:write`. New constant classes: `ItemImportKinds`, `ItemImportSources`,
`ItemImportStatuses` and `ItemImportSkipReasons`.

    $queued = $client->calendar->importIcs(new SplFileInfo('work.ics'), filename: 'work.ics');

    $done = $client->calendar->getImport($queued['id']);
    echo $done['status'], ' ', $done['counts']['imported'], ' ', $done['counts']['skipped'], PHP_EOL;

    $client->calendar->undoImport($queued['id']);

A key can read its own request log, its own history and its own figures with no scope at all, so a
key that can only send can still watch itself. `me->listRequests()`, `me->listActivity()` and
`me->stats()` read the calling key and nothing else, and the two lists come with
`listAllRequests()`, `iterateRequests()`, `listAllActivity()` and `iterateActivity()`.
`keys->listRequests()`, `keys->listWorkspaceRequests()` and `keys->stats()` are still the ones that
reach other keys, behind `keys:read`. `me->stats()` fills `sends` only when the key holds
`emails:read`. Without that scope it is `null`, never zeros. An OAuth access token is refused with
403 `api_key_only`, because calls made with one are not logged. Every reader of the request log,
the six in `keys` and the three in `me`, also takes two more filters. `statuses:` keeps the calls
answered with one of up to 20 exact HTTP status codes, as an array or one comma-separated string,
and `path:` keeps the calls to one route, whatever the method. `path:` matches the route, not the
exact path: an id counts as `:id` and an email address as `:address`, so `/emails/:id` reads the
calls made to any single email, and a path that ends in `*` reads every route that starts with it.
Both combine with `failedOnly:`, `since:` and `until:`. On the readers in `keys` the new arguments
sit after `failedOnly:` and before `since:`, `until:`, `limit:`, `cursor:` and `apiKey:`, so pass
the arguments after them by name.

    $throttled = $client->me->listRequests(statuses: [429], since: '2026-10-01T00:00:00Z');
    echo count($throttled), PHP_EOL;

    $stats = $client->me->stats(grain: 'hour');
    echo $stats['sends']['totals']['sends'] ?? 'this key cannot read its sends', PHP_EOL;

    foreach ($client->keys->listWorkspaceRequests(path: '/emails', statuses: [403, 422]) as $call) {
        echo $call['keyName'], ' ', $call['status'], ' ', $call['errorCode'] ?? '', PHP_EOL;
    }

An `automations` namespace holds emails and other steps that run by themselves for each contact: a
welcome series when somebody joins an audience, a nudge after a trial starts, a note on a birthday.
An automation has a trigger and steps (send an email from a template, wait, branch, add to or remove
from an audience, write a custom field, call a webhook, exit). You edit a draft `definition` and
`publish()` it, which saves a numbered version and sets the automation live, and each contact stays
on the version they entered on. `list()`, `listAll()`, `iterate()`, `get()`, `create()`, `update()`
and `delete()` manage them, `listStarters()` returns ready-made definitions, `pause()`, `resume()`,
`archive()` and `duplicate()` change their state, `sendTest()` sends the email of one step to one
address, `listVersions()` and `restoreVersion()` read the published versions and copy one back into
the draft, and `stats()` returns totals, per-step numbers and a daily series. `listEnrollments()`,
`listAllEnrollments()`, `iterateEnrollments()` and `getEnrollment()` read who is in an automation
and what each step did for them, `enroll()` puts a contact in and `exitEnrollment()` takes one out.
Reading needs `automations:read` and every change `automations:write`. `publish()`, `resume()` and
`sendTest()` also need `emails:send`. A draft that cannot run is refused with 422
`invalid_automation`, and `problems` on the error lists every problem that blocks it. New constant
classes: `AutomationStatuses`, `AutomationTriggerKinds`, `AutomationStepKinds`,
`AutomationWaitModes`, `AutomationDurationUnits`, `AutomationConditionKinds`,
`AutomationFieldOperators`, `AutomationDateFields`, `AutomationValueSources`,
`AutomationEnrollmentStatuses`, `AutomationEnrollmentSources`, `AutomationExitReasons`,
`AutomationHoldReasons`, `AutomationPauseReasons` and `AutomationRunOutcomes`.

    $automation = $client->automations->create(['name' => 'Welcome series', 'starter' => 'welcome-series']);

    foreach ($automation['problems'] as $problem) {
        echo $problem['path'], ': ', $problem['message'], PHP_EOL;
    }

    $live = $client->automations->publish($automation['id']);
    echo $live['status'], ', version ', $live['publishedVersion'], PHP_EOL;

An `events` namespace records what your contacts did, from your own code. `send()` stores one event
against a contact, starts every live automation whose trigger names it and ends the waits that were
holding out for it. `sendBatch()` records up to 100 in one call and reports per event, and
`listNames()` returns the event names the workspace has seen. Both sends carry an `Idempotency-Key`,
generated per call or passed as `idempotencyKey:`, so a retry never records an event twice.
`contacts->listEvents()`, `listAllEvents()` and `iterateEvents()` read the events of one contact.
Sending needs `contacts:write`, reading the events of a contact `contacts:read`, and `listNames()`
`automations:read`. A key or an app may send 600 events a minute.

    $event = $client->events->send([
        'name' => 'order.placed',
        'email' => 'ada@example.com',
        'properties' => ['orderId' => 'AC-4192'],
        'createContact' => true,
    ]);
    echo count($event['enrolled']), ' automations started', PHP_EOL;

`WebhookEvents` gained `AUTOMATION_ENTERED`, `AUTOMATION_EXITED`, `AUTOMATION_PAUSED` and
`AUTOMATION_WEBHOOK`. Like `form.*`, the events belong to the whole workspace, have to be named in
`eventTypes`, and never reach an endpoint limited to some addresses or domains.

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
