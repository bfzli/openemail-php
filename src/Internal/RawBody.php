<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Exception\InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

final class RawBody
{
    private const CHUNK = 65536;

    private const MEMORY_SCHEME = 'php://';

    public static function isReadable(mixed $data): bool
    {
        return \is_string($data)
            || (\is_resource($data) && get_resource_type($data) === 'stream')
            || $data instanceof \SplFileInfo
            || $data instanceof StreamInterface
            || $data instanceof UploadedFileInterface;
    }

    public static function read(mixed $data): string
    {
        if (\is_string($data)) {
            return $data;
        }

        if ($data instanceof UploadedFileInterface) {
            return self::read(self::uploadedStream($data));
        }

        if ($data instanceof StreamInterface) {
            return self::readStream($data);
        }

        if ($data instanceof \SplFileObject && self::isMemoryFile($data)) {
            return self::readFileObject($data, 0, null);
        }

        if ($data instanceof \SplFileInfo) {
            return self::readFile($data->getPathname());
        }

        if (\is_resource($data) && get_resource_type($data) === 'stream') {
            self::assertReadable($data);

            if (stream_get_meta_data($data)['seekable']) {
                rewind($data);
            }

            $contents = stream_get_contents($data);

            if ($contents === false) {
                throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, self::describe($data)));
            }

            return $contents;
        }

        throw new InvalidArgumentException(Messages::RAW_BODY_SHAPE);
    }

    public static function size(mixed $data): int
    {
        if (\is_string($data)) {
            return \strlen($data);
        }

        if ($data instanceof UploadedFileInterface) {
            return self::size(self::uploadedStream($data));
        }

        if ($data instanceof StreamInterface) {
            return $data->getSize() ?? \strlen(self::read($data));
        }

        if (self::isMemoryFile($data)) {
            return \strlen(self::read($data));
        }

        if ($data instanceof \SplFileInfo) {
            if (is_dir($data->getPathname())) {
                throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, $data->getPathname() . ' is a directory'));
            }

            $size = @filesize($data->getPathname());

            if ($size === false) {
                throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, $data->getPathname()));
            }

            return $size;
        }

        if (\is_resource($data) && get_resource_type($data) === 'stream') {
            self::assertReadable($data);
            $stat = fstat($data);

            if (\is_array($stat) && ($stat['mode'] & 0o170000) === 0o100000) {
                return $stat['size'];
            }

            return \strlen(self::read($data));
        }

        throw new InvalidArgumentException(Messages::RAW_BODY_SHAPE);
    }

    public static function slice(mixed $data, int $offset, int $length): string
    {
        if (\is_string($data)) {
            return substr($data, $offset, $length);
        }

        if ($data instanceof UploadedFileInterface) {
            return self::slice(self::uploadedStream($data), $offset, $length);
        }

        if ($data instanceof StreamInterface) {
            if ($data->isSeekable()) {
                $data->seek($offset);

                return self::readStreamInterface($data, $length);
            }

            return substr(self::read($data), $offset, $length);
        }

        if ($data instanceof \SplFileObject && self::isMemoryFile($data)) {
            return self::readFileObject($data, $offset, $length);
        }

        if ($data instanceof \SplFileInfo) {
            $handle = @fopen($data->getPathname(), 'rb');

            if ($handle === false) {
                throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, $data->getPathname()));
            }

            try {
                fseek($handle, $offset);

                return self::readResource($handle, $length);
            } finally {
                fclose($handle);
            }
        }

        if (\is_resource($data) && get_resource_type($data) === 'stream') {
            self::assertReadable($data);
            $meta = stream_get_meta_data($data);

            if ($meta['seekable'] && fseek($data, $offset) === 0) {
                return self::readResource($data, $length);
            }

            return substr(self::read($data), $offset, $length);
        }

        throw new InvalidArgumentException(Messages::RAW_BODY_SHAPE);
    }

    public static function contentType(mixed $data, ?string $explicit): ?string
    {
        if ($explicit !== null && $explicit !== '') {
            return $explicit;
        }

        $declared = self::declaredType($data);

        if ($declared !== null) {
            return $declared;
        }

        $name = self::fileName($data);

        if ($name === null) {
            return null;
        }

        return Defaults::CONTENT_TYPES_BY_EXTENSION[strtolower(pathinfo($name, PATHINFO_EXTENSION))] ?? null;
    }

    public static function fileName(mixed $data): ?string
    {
        if ($data instanceof UploadedFileInterface) {
            $name = $data->getClientFilename();

            return $name !== null && $name !== '' ? $name : null;
        }

        if (self::isMemoryFile($data)) {
            return null;
        }

        if ($data instanceof \SplFileInfo) {
            $original = method_exists($data, 'getClientOriginalName') ? $data->getClientOriginalName() : null;

            return \is_string($original) && $original !== '' ? $original : $data->getFilename();
        }

        if ($data instanceof StreamInterface) {
            return self::nameFromUri($data->getMetadata('uri'));
        }

        if (\is_resource($data) && get_resource_type($data) === 'stream') {
            return self::nameFromUri(stream_get_meta_data($data)['uri'] ?? null);
        }

        return null;
    }

    private static function declaredType(mixed $data): ?string
    {
        if ($data instanceof UploadedFileInterface) {
            $type = $data->getClientMediaType();

            return $type !== null && $type !== '' ? $type : null;
        }

        if ($data instanceof \SplFileInfo && method_exists($data, 'getClientMimeType')) {
            $type = $data->getClientMimeType();

            return \is_string($type) && $type !== '' ? $type : null;
        }

        return null;
    }

    private static function nameFromUri(mixed $uri): ?string
    {
        if (!\is_string($uri) || $uri === '' || str_starts_with($uri, 'php://')) {
            return null;
        }

        $name = basename($uri);

        return $name !== '' ? $name : null;
    }

    public static function sliceable(mixed $data): mixed
    {
        if ($data instanceof UploadedFileInterface) {
            return self::sliceable(self::uploadedStream($data));
        }

        if ($data instanceof StreamInterface) {
            return $data->isSeekable() ? $data : self::readStream($data);
        }

        if (self::isMemoryFile($data)) {
            return self::read($data);
        }

        if (\is_resource($data) && get_resource_type($data) === 'stream' && !stream_get_meta_data($data)['seekable']) {
            return self::read($data);
        }

        return $data;
    }

    private static function uploadedStream(UploadedFileInterface $file): StreamInterface
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException(\sprintf(Messages::UPLOAD_FAILED, $file->getError()));
        }

        try {
            return $file->getStream();
        } catch (\RuntimeException $error) {
            throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, $error->getMessage()), 0, $error);
        }
    }

    private static function readStream(StreamInterface $stream): string
    {
        try {
            if (!$stream->isReadable()) {
                throw new \RuntimeException('the stream is closed, detached or open for writing only');
            }

            if ($stream->isSeekable()) {
                $stream->rewind();
            }

            return $stream->getContents();
        } catch (\RuntimeException $error) {
            throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, $error->getMessage()), 0, $error);
        }
    }

    private static function isMemoryFile(mixed $data): bool
    {
        return $data instanceof \SplFileObject && str_starts_with($data->getPathname(), self::MEMORY_SCHEME);
    }

    private static function readFileObject(\SplFileObject $file, int $offset, ?int $length): string
    {
        if ($file->ftell() !== $offset) {
            @$file->fseek($offset);
        }

        $contents = '';

        while (($length === null || \strlen($contents) < $length) && !$file->eof()) {
            $chunk = $file->fread($length === null ? self::CHUNK : min(self::CHUNK, max(1, $length - \strlen($contents))));

            if ($chunk === false || $chunk === '') {
                break;
            }

            $contents .= $chunk;
        }

        return $contents;
    }

    private static function assertReadable(mixed $stream): void
    {
        if (!\is_resource($stream)) {
            return;
        }

        $mode = stream_get_meta_data($stream)['mode'];

        if (!str_contains($mode, 'r') && !str_contains($mode, '+')) {
            throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, self::describe($stream) . ' is open for writing only'));
        }
    }

    private static function readFile(string $path): string
    {
        if (is_dir($path)) {
            throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, $path . ' is a directory'));
        }

        $contents = @file_get_contents($path);

        if ($contents === false) {
            throw new InvalidArgumentException(\sprintf(Messages::RAW_BODY_UNREADABLE, $path));
        }

        return $contents;
    }

    private static function readResource(mixed $handle, int $length): string
    {
        $contents = '';

        while (\is_resource($handle) && \strlen($contents) < $length && !feof($handle)) {
            $chunk = fread($handle, min(self::CHUNK, max(1, $length - \strlen($contents))));

            if ($chunk === false || $chunk === '') {
                break;
            }

            $contents .= $chunk;
        }

        return $contents;
    }

    private static function readStreamInterface(StreamInterface $stream, int $length): string
    {
        $contents = '';

        while (\strlen($contents) < $length && !$stream->eof()) {
            $chunk = $stream->read(min(self::CHUNK, $length - \strlen($contents)));

            if ($chunk === '') {
                break;
            }

            $contents .= $chunk;
        }

        return $contents;
    }

    private static function describe(mixed $stream): string
    {
        $uri = \is_resource($stream) ? (stream_get_meta_data($stream)['uri'] ?? null) : null;

        return \is_string($uri) ? $uri : 'stream';
    }
}
