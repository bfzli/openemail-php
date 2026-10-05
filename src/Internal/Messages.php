<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

final class Messages
{
    public const API_KEY_REQUIRED = 'An OpenEmail API key is required. Pass apiKey or set OPENEMAIL_API_KEY. Create one in OpenEmail under Settings, API keys.';
    public const CREDENTIAL_REQUIRED = 'An OpenEmail API key or OAuth access token is required. Pass apiKey or accessToken, or set OPENEMAIL_API_KEY or OPENEMAIL_ACCESS_TOKEN. Create a key in OpenEmail under Settings, API keys.';
    public const CREDENTIAL_CONFLICT = 'Pass apiKey or accessToken, not both. Every request carries one credential, so the client cannot tell which of the two you meant.';
    public const API_KEY_SHAPE = 'is not an OpenEmail API key. The API accepts only keys beginning "oe_live_" or "oe_test_", so a session cookie, a session token or a key for another service is refused. Pass an OAuth access token as accessToken instead.';
    public const ACCESS_TOKEN_SHAPE = 'is not an OAuth access token. Pass the access token OpenEmail issued when the app was connected: a string of 1 to 512 characters that does not begin "oe_". An API key goes in apiKey instead.';
    public const API_KEY_SUBJECT = 'The apiKey';
    public const CALL_API_KEY_SUBJECT = 'The apiKey passed to this call';
    public const ACCESS_TOKEN_SUBJECT = 'The accessToken';
    public const ACCESS_TOKEN_PROVIDED = 'The value the accessToken callable returned';
    public const BASE_URL_SHAPE = 'is not a usable base URL. Pass an https origin such as "https://api.openemail.uk". Plain http is for a server on this machine only, at localhost, a 127.x.x.x address or ::1, because the client never sends a credential over plain http to any other host.';
    public const BASE_URL_USERINFO = 'A base URL must not carry a user name or password. Pass the origin alone and the credential as apiKey or accessToken.';
    public const CLEARTEXT = 'Refused to send a credential to %s over plain http, where anyone on the network can read it. Use an https base URL. Plain http is accepted only for a server on this machine: localhost, a 127.x.x.x address or ::1.';
    public const UNSPECIFIED_HOST = '%s names %s, an address a server listens on, not one to send requests to. For a server on this machine, use %s.';
    public const PATH_SHAPE = 'is not a usable request path. Pass a path on the API that begins with a single "/", such as "/threads". Anything else could send the request, and the credential it carries, to another host.';
    public const OFF_ORIGIN = 'leaves the API origin, so the request was not sent. Pass a path on the API that begins with a single "/", such as "/threads".';
    public const EMPTY_SEGMENT = 'An id must not be empty.';
    public const DOT_SEGMENT = 'is not a usable id: a path segment made only of dots is removed by URL parsers, so the request would reach a different endpoint.';
    public const SEGMENT_ENCODING = 'An id must be valid UTF-8 text.';
    public const FILENAME_REQUIRED = 'A file name is required. Pass it as filename, the name the file is stored and downloaded under.';
    public const RAW_BODY_SHAPE = 'Pass the bytes as a string, a stream resource, an SplFileInfo or a PSR-7 stream.';
    public const RAW_BODY_UNREADABLE = 'The file or stream passed as the bytes could not be read: %s';
    public const HEADER_SHAPE = 'A header name must be a token, and a header value must not contain a line break or any other control character.';
    public const METHOD_SHAPE = 'An HTTP method must be a token, such as "GET" or "DELETE".';
    public const TIMEOUT_SHAPE = 'Pass timeout as a number of seconds, or 0 for no timeout.';
    public const BODY_DEPTH = 'The request body nests more than %d levels deep or contains itself, so it cannot be encoded as JSON. Pass arrays and values that never refer back to themselves.';
    public const UPLOAD_FAILED = 'The uploaded file did not arrive: PHP reported upload error %d. Pass a file that finished uploading.';
    public const CURL_OPTION_KEY = 'The keys of curlOptions must be CURLOPT_ constants.';
    public const CURL_OPTION_TARGET = 'curlOptions cannot set CURLOPT_REQUEST_TARGET, because the path of every request comes from its URL. Leave it out.';
    public const CURL_OPTION = 'cURL refused curlOptions entry %s: %s. Pass each option as a CURLOPT_ constant with a value of the type cURL expects.';
    public const ATTACHMENT_CONTENT_SHAPE = 'An attachment\'s content must be base64 text. Encode raw bytes with OpenEmail::toBase64(), or pass an SplFileInfo, a stream resource or a PSR-7 stream, which are read and encoded for you.';
    public const IMPORT_FILE_SHAPE = 'Each file to import must be an array with a name and its data: a string of bytes, a stream resource, an SplFileInfo or a PSR-7 stream.';
    public const BATCH_EMAIL_SHAPE = 'Each email in a batch must be an array, the same shape emails->send takes.';
    public const ATTACHMENT_SHAPE = 'Each attachment must be an array with filename and content, or with fileId for a file already uploaded to the workspace.';
    public const JSON_ENCODE = 'The request body could not be encoded as JSON: %s. Text must be valid UTF-8 and numbers must be finite.';
    public const BODY_VALUE_SHAPE = 'A request body can hold arrays and other iterables, strings, numbers, booleans, null, dates, enums and JSON-serializable objects, not %s.';
    public const ACCESS_TOKEN_CALLABLE = 'Pass accessToken as a string or as a callable that returns one.';
    public const TIMED_OUT = 'Request timed out after %ss.';
    public const TIMED_OUT_IN_CLIENT = 'Request timed out in the wrapped HTTP client, whose own timeout applies: %s';
    public const UNREACHABLE = 'Could not reach %s: %s';
    public const NOT_JSON = 'The API answered %d with a body that is not JSON.';
    public const NOT_JSON_OBJECT = 'The API answered with JSON that is not an object or a list.';
    public const UNKNOWN_QUERY_KEY = 'No query key is registered for %s.';
    public const UNRECOGNISED_BODY = 'The API responded %d with a body this client did not recognise.';
    public const UNKNOWN_FAILURE = 'the request failed without a reason.';
    public const CURL_REQUIRED = 'The cURL extension is not loaded, and the OpenEmail SDK requires it. Enable ext-curl in the php.ini this PHP reads.';
    public const CURL_HANDLE = 'cURL could not create a handle for the request.';
    public const FACTORY_REQUIRED = 'Psr18HttpClient needs PSR-17 factories to build requests. Pass requestFactory and streamFactory, or install a PSR-17 implementation such as nyholm/psr7 so one can be found.';
    public const WEBHOOK_MISSING_HEADER = 'Missing the X-OpenEmail-Signature header.';
    public const WEBHOOK_MALFORMED_HEADER = 'X-OpenEmail-Signature is not in the form t=<seconds>,v1=<hex>.';
    public const WEBHOOK_OUT_OF_TOLERANCE = 'Webhook timestamp is %ss out of tolerance (%ss).';
    public const WEBHOOK_MISMATCH = 'Webhook signature does not match the payload.';
    public const WEBHOOK_SECRET_REQUIRED = 'A webhook signing secret is required. Pass the secret the webhook was created with as secret.';
    public const WEBHOOK_PAYLOAD_SHAPE = 'The verified payload is not JSON, so it is not an OpenEmail event.';
    public const UPDATE_AVAILABLE = '%s %s %s is available, you are on %s. %s';
}
