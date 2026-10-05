<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Exception\InvalidArgumentException;

final class Credentials
{
    public static function isApiKey(#[\SensitiveParameter] mixed $value): bool
    {
        if (!\is_string($value)) {
            return false;
        }

        foreach (Protocol::API_KEY_PREFIXES as $prefix) {
            if (str_starts_with($value, $prefix)) {
                return true;
            }
        }

        return false;
    }

    public static function isAccessToken(#[\SensitiveParameter] mixed $value): bool
    {
        if (!\is_string($value) || $value === '' || str_starts_with($value, Protocol::ACCESS_TOKEN_RULES['RESERVED_PREFIX'])) {
            return false;
        }

        $length = preg_match_all('/./su', $value);

        return \is_int($length) && $length <= Protocol::ACCESS_TOKEN_RULES['MAX_LENGTH'];
    }

    public static function isPresent(#[\SensitiveParameter] mixed $value): bool
    {
        return $value !== null && $value !== '';
    }

    public static function modeOf(#[\SensitiveParameter] mixed $apiKey): string
    {
        if (\is_string($apiKey) && str_starts_with($apiKey, Protocol::API_KEY_PREFIXES['TEST'])) {
            return Protocol::API_KEY_MODES['TEST'];
        }

        return Protocol::API_KEY_MODES['LIVE'];
    }

    public static function assertApiKey(#[\SensitiveParameter] mixed $apiKey, string $subject): void
    {
        if (!self::isPresent($apiKey)) {
            throw new InvalidArgumentException(Messages::API_KEY_REQUIRED);
        }

        if (!self::isApiKey($apiKey)) {
            throw new InvalidArgumentException($subject . ' ' . Messages::API_KEY_SHAPE);
        }
    }

    public static function assertAccessToken(#[\SensitiveParameter] mixed $accessToken, string $subject): void
    {
        if ($accessToken instanceof \Closure) {
            return;
        }

        if (!self::isAccessToken($accessToken)) {
            throw new InvalidArgumentException($subject . ' ' . Messages::ACCESS_TOKEN_SHAPE);
        }
    }

    public static function assertCredential(
        #[\SensitiveParameter]
        mixed $apiKey,
        #[\SensitiveParameter]
        mixed $accessToken,
        bool $required,
    ): void {
        $withKey = self::isPresent($apiKey);
        $withToken = self::isPresent($accessToken);

        if ($withKey && $withToken) {
            throw new InvalidArgumentException(Messages::CREDENTIAL_CONFLICT);
        }

        if ($withKey) {
            self::assertApiKey($apiKey, Messages::API_KEY_SUBJECT);
        } elseif ($withToken) {
            self::assertAccessToken($accessToken, Messages::ACCESS_TOKEN_SUBJECT);
        } elseif ($required) {
            throw new InvalidArgumentException(Messages::CREDENTIAL_REQUIRED);
        }
    }

    public static function provider(#[\SensitiveParameter] mixed $accessToken): string|\Closure|null
    {
        if ($accessToken === null || \is_string($accessToken) || $accessToken instanceof \Closure) {
            return $accessToken;
        }

        if (\is_callable($accessToken)) {
            return \Closure::fromCallable($accessToken);
        }

        throw new InvalidArgumentException(Messages::ACCESS_TOKEN_CALLABLE);
    }

    public static function fromEnvironment(string $name): ?string
    {
        $value = getenv($name);

        if (\is_string($value) && $value !== '') {
            return $value;
        }

        $server = $_SERVER[$name] ?? $_ENV[$name] ?? null;

        return \is_string($server) && $server !== '' ? $server : null;
    }
}
