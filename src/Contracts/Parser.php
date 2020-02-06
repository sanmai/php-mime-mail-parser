<?php

namespace PhpMimeMailParser\Contracts;

interface Parser
{
    public function setPath(string $path): Parser;
    public function setStream($stream): Parser;
    public function setText(string $data): Parser;

    public function getRawHeader($name);
    public function getHeader($name): ?string;
    public function getHeaders(): iterable;

    public function getHeadersRaw(): string;

    public function getMessageBody(string $type = 'text'): string;
    public function getAddresses(string $name): iterable;
    public function getInlineParts(string $type = 'text'): iterable;

    public function getAttachments(bool $include_inline = true): iterable;

    public function saveAttachments(
        string $attach_dir,
        bool $include_inline = true,
        string $filenameStrategy = self::ATTACHMENT_DUPLICATE_SUFFIX
    ): iterable;

    public function getResource();
    public function getStream();
    public function getData(): ?string;

    public function getParts(): iterable;
    public function getCharset(): CharsetManager;
    public function addMiddleware(callable $middleware): void;
}
