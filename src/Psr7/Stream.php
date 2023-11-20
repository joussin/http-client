<?php

namespace Joussin\Component\HttpClient\Psr7;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{

    /**
     * @see http://php.net/manual/function.fopen.php
     * @see http://php.net/manual/en/function.gzopen.php
     */
    private const READABLE_MODES = '/r|a\+|ab\+|w\+|wb\+|x\+|xb\+|c\+|cb\+/';
    private const WRITABLE_MODES = '/a|w|r\+|rb\+|rw|x|c/';

    /** @var resource */
    private $stream;
    /** @var int|null */
    private $size;
    /** @var bool */
    private $seekable;
    /** @var bool */
    private $readable;
    /** @var bool */
    private $writable;
    /** @var string|null */
    private $uri;
    /** @var mixed[] */
    private $customMetadata;

    /**
     * @param $stream
     * @param array $options - array{size?: int, metadata?: array}
     */
    public function __construct($stream, array $options = [])
    {
        $this->stream = $stream;

        $meta = stream_get_meta_data($this->stream);
        $this->seekable = $meta['seekable'];
        $this->readable = (bool) preg_match(self::READABLE_MODES, $meta['mode']);
        $this->writable = (bool) preg_match(self::WRITABLE_MODES, $meta['mode']);
    }



    public static function fromResource($data, bool $isFile = false, array $options = [])
    {
        if(is_resource($data) || is_object($data))
        {
            $resource = self::resourceToResource($data);
        }
        else if(is_string($data) && $isFile)
        {
            $resource = self::fileToResource($data, 'r');
        }
        else if(is_scalar($data))
        {
            $resource = self::textContentToResource($data);
        }
        else // if(is_null($data))
        {
            $resource = self::resource();
        }

        return new self($resource, $options);
    }


    public static function resourceToResource(string $resource = '')
    {
        if ($resource instanceof StreamInterface) {}
        return $resource;
    }

    public static function textContentToResource(string $content = '')
    {
        $stream = self::resource();
        if ($content !== '') {
            fwrite($stream, (string) $content);
            fseek($stream, 0);
            $resource = $stream;
        }
        return $resource;
    }


    public static function fileToResource(string $filename, string $mode = 'r')
    {
        $resource = self::resource($filename, $mode);
        try {
            /** @var resource $resource */
            if ((\stream_get_meta_data($resource)['uri'] ?? '') === 'php://input') {
                $stream = self::resource('php://temp', 'w+');
                stream_copy_to_stream($resource, $stream);
                fseek($stream, 0);
                $resource = $stream;
            }
        } catch (\RuntimeException $e) {
            throw $e;
        }
        return $resource;
    }


    private static function resource($filename= 'php://temp', $mode = 'r+')
    {
        try {
            return fopen($filename, $mode);
        } catch (\Exception $e) {
            throw new \Exception();
        }
    }








    public function __toString(): string
    {
        return json_encode([]);
    }

    public function close(): void
    {
        if (isset($this->stream)) {
            if (is_resource($this->stream)) {
                fclose($this->stream);
            }
            $this->detach();
        }
    }

    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $result = $this->stream;
        unset($this->stream);
        $this->size = $this->uri = null;
        $this->readable = $this->writable = $this->seekable = false;

        return $result;
    }

    public function getSize(): ?int
    {
        if ($this->size !== null) {
            return $this->size;
        }

        if (!isset($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);
        if (is_array($stats) && isset($stats['size'])) {
            $this->size = $stats['size'];

            return $this->size;
        }

        return null;
    }

    public function tell(): int
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        $result = ftell($this->stream);

        if ($result === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    public function eof(): bool
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        return feof($this->stream);
    }


    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function isSeekable(): bool
    {
        return $this->seekable;
    }



    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        $whence = (int) $whence;

        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }
        if (!$this->seekable) {
            throw new \RuntimeException('Stream is not seekable');
        }
        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new \RuntimeException('Unable to seek to stream position '
                .$offset.' with whence '.var_export($whence, true));
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }


    public function write(string $string): int
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }
        if (!$this->writable) {
            throw new \RuntimeException('Cannot write to a non-writable stream');
        }

        // We can't know the size after writing anything
        $this->size = null;
        $result = fwrite($this->stream, $string);

        if ($result === false) {
            throw new \RuntimeException('Unable to write to stream');
        }

        return $result;
    }


    public function read(int $length): string
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }
        if (!$this->readable) {
            throw new \RuntimeException('Cannot read from non-readable stream');
        }
        if ($length < 0) {
            throw new \RuntimeException('Length parameter cannot be negative');
        }

        if (0 === $length) {
            return '';
        }

        try {
            $string = fread($this->stream, $length);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to read from stream', 0, $e);
        }

        if (false === $string) {
            throw new \RuntimeException('Unable to read from stream');
        }

        return $string;
    }

    public function getContents(): string
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        if (!$this->readable) {
            throw new \RuntimeException('Cannot read from non-readable stream');
        }

        try {
            /** @var string|false $contents */
            $contents = stream_get_contents($this->stream);

            if ($contents === false) {
                throw new \Exception('Unable to read stream contents');
            }
        } catch (\Exception $e) {
            throw new \Exception('Unable to read stream contents');
        }


        return $contents;
    }

    public function getMetadata(string $key = null)
    {
        if (!isset($this->stream)) {
            return $key ? null : [];
        } elseif (!$key) {
            return $this->customMetadata + stream_get_meta_data($this->stream);
        } elseif (isset($this->customMetadata[$key])) {
            return $this->customMetadata[$key];
        }

        $meta = stream_get_meta_data($this->stream);

        return $meta[$key] ?? null;
    }
}