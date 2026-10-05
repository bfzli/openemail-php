<?php

declare(strict_types=1);

namespace OpenEmail\Http;

use GuzzleHttp\ClientInterface as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory as GuzzleFactory;
use Http\Discovery\Exception as DiscoveryException;
use Http\Discovery\Psr17FactoryDiscovery;
use Nyholm\Psr7\Factory\Psr17Factory as NyholmFactory;
use OpenEmail\Exception\InvalidArgumentException;
use OpenEmail\Internal\Defaults;
use OpenEmail\Internal\Messages;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Psr18HttpClient implements HttpClient
{
    private readonly RequestFactoryInterface $requestFactory;

    private readonly StreamFactoryInterface $streamFactory;

    public function __construct(
        private readonly ClientInterface $client,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->requestFactory = $requestFactory ?? self::requestFactoryFor($client);
        $this->streamFactory = $streamFactory ?? self::streamFactoryFor($client);
    }

    public function send(#[\SensitiveParameter] HttpRequest $request): HttpResponse
    {
        try {
            $message = $this->messageFor($request);
        } catch (DiscoveryException $error) {
            throw new InvalidArgumentException(Messages::FACTORY_REQUIRED, 0, $error);
        }

        try {
            $response = $this->dispatch($message, $request->timeout);
        } catch (ClientExceptionInterface $error) {
            throw new HttpClientException($error->getMessage(), self::isTimeout($error));
        }

        $headers = [];

        foreach ($response->getHeaders() as $name => $values) {
            $headers[strtolower((string) $name)] = implode(', ', $values);
        }

        return new HttpResponse($response->getStatusCode(), $headers, (string) $response->getBody());
    }

    public function appliesTimeout(): bool
    {
        return $this->client instanceof GuzzleClient;
    }

    private function messageFor(#[\SensitiveParameter] HttpRequest $request): RequestInterface
    {
        $message = $this->requestFactory->createRequest($request->method, $request->url);

        foreach ($request->headers as $name => $value) {
            if (\is_string($value)) {
                $message = $message->withHeader((string) $name, $value);
            }
        }

        if ($request->body !== null) {
            return $message->withBody($this->streamFactory->createStream($request->body));
        }

        if (\in_array($request->method, Defaults::BODYLESS_WRITES, true)) {
            return $message->withHeader('Content-Length', '0')->withBody($this->streamFactory->createStream(''));
        }

        return $message;
    }

    private function dispatch(#[\SensitiveParameter] RequestInterface $message, ?float $timeout): ResponseInterface
    {
        if (!$this->client instanceof GuzzleClient) {
            return $this->client->sendRequest($message);
        }

        $options = ['http_errors' => false, 'allow_redirects' => false];

        if ($timeout !== null && $timeout > 0) {
            $options['timeout'] = $timeout;
            $options['connect_timeout'] = $timeout;
        }

        return $this->client->send($message, $options);
    }

    private static function isTimeout(ClientExceptionInterface $error): bool
    {
        return preg_match(Defaults::TIMEOUT_MESSAGE, $error->getMessage()) === 1;
    }

    private static function requestFactoryFor(ClientInterface $client): RequestFactoryInterface
    {
        if ($client instanceof RequestFactoryInterface) {
            return $client;
        }

        return self::discoveredRequestFactory() ?? self::bundledFactory();
    }

    private static function streamFactoryFor(ClientInterface $client): StreamFactoryInterface
    {
        if ($client instanceof StreamFactoryInterface) {
            return $client;
        }

        return self::discoveredStreamFactory() ?? self::bundledFactory();
    }

    private static function discoveredRequestFactory(): ?RequestFactoryInterface
    {
        if (!class_exists(Psr17FactoryDiscovery::class)) {
            return null;
        }

        try {
            return Psr17FactoryDiscovery::findRequestFactory();
        } catch (DiscoveryException) {
            return null;
        }
    }

    private static function discoveredStreamFactory(): ?StreamFactoryInterface
    {
        if (!class_exists(Psr17FactoryDiscovery::class)) {
            return null;
        }

        try {
            return Psr17FactoryDiscovery::findStreamFactory();
        } catch (DiscoveryException) {
            return null;
        }
    }

    private static function bundledFactory(): RequestFactoryInterface&StreamFactoryInterface
    {
        if (class_exists(NyholmFactory::class)) {
            return new NyholmFactory();
        }

        if (class_exists(GuzzleFactory::class)) {
            return new GuzzleFactory();
        }

        throw new InvalidArgumentException(Messages::FACTORY_REQUIRED);
    }
}
