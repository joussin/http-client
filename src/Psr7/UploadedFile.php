<?php

namespace Joussin\Component\HttpClient\Psr7;

use Psr\Http\Message\StreamInterface;

class UploadedFile implements \Psr\Http\Message\UploadedFileInterface
{

    /**
     * @inheritDoc
     */
    public function getStream(): StreamInterface
    {
        // TODO: Implement getStream() method.
    }

    /**
     * @inheritDoc
     */
    public function moveTo(string $targetPath): void
    {
        // TODO: Implement moveTo() method.
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        // TODO: Implement getSize() method.
    }

    /**
     * @inheritDoc
     */
    public function getError(): int
    {
        // TODO: Implement getError() method.
    }

    /**
     * @inheritDoc
     */
    public function getClientFilename(): ?string
    {
        // TODO: Implement getClientFilename() method.
    }

    /**
     * @inheritDoc
     */
    public function getClientMediaType(): ?string
    {
        // TODO: Implement getClientMediaType() method.
    }
}