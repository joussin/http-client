<?php

namespace Joussin\Component\HttpClient\Psr7;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface, \JsonSerializable
{

    /** @var string Uri scheme. */
    private $scheme = '';

    /** @var string getAuthority. */
    private $authority = '';


    /** @var string Uri user info. */
    private $userInfo = '';

    /** @var string Uri host. */
    private $host = '';

    /** @var int|null Uri port. */
    private $port;

    /** @var string Uri path. */
    private $path = '';

    /** @var string Uri query string. */
    private $query = '';

    /** @var string Uri fragment. */
    private $fragment = '';


protected $composed = null;

    public function __construct(string $uri = '')
    {
        if ($uri !== '') {
            $this->init($uri);
        }
    }


    /**
     * Retrieve the user information component of the URI.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    private function userInfo(string $user = '', string $pass = '') : string
    {
        $userInfo = '';

        if($user != '')
        {
            $userInfo .= $user;
        }
        if($pass != '')
        {
            $userInfo .= ':' . $pass;
        }

        return $userInfo;
    }


    /**
     * Retrieve the authority component of the URI.
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function authority(string $userInfo = '', string $host = '', string $port = ''): string
    {
        $authority = '';

        if($userInfo != '')
        {
            $authority .= $userInfo . '@';
        }
        if($host != '')
        {
            $authority .= $host;
        }
        if($port != '')
        {
            $authority .= ':' . $port;
        }

        return $authority;
    }


    private function parse_url(string $url, int $component, string $default = '') : string
    {
        $result = parse_url($url, $component) ?? $default;
        return (string) $result ;
    }

    private function init($url) : void
    {
        $this->scheme = $this->parse_url($url, PHP_URL_SCHEME);

        $this->host = $this->parse_url($url, PHP_URL_HOST);
        $this->port = $this->parse_url($url, PHP_URL_PORT);

        $user = $this->parse_url($url, PHP_URL_USER);
        $pass = $this->parse_url($url, PHP_URL_PASS);

        $this->userInfo = $this->userInfo($user, $pass);

        $this->authority = $this->authority($this->userInfo, $this->host, $this->port);

        $this->path = $this->parse_url($url, PHP_URL_PATH);
        $this->query = $this->parse_url($url, PHP_URL_QUERY);
        $this->fragment = $this->parse_url($url, PHP_URL_FRAGMENT);
    }


    /**
     * Retrieve the scheme component of the URI.
     *
     * If no scheme is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URI scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority(): string
    {
        return $this->authority;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * If no host is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string The URI host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard port
     * used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return null|int The URI port.
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Retrieve the path component of the URI.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     *
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URI path.
     */
    public function getPath():string
    {
        return $this->path;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * If no query string is present, this method MUST return an empty string.
     *
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     *
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string The URI query string.
     */
    public function getQuery():string
    {
        return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * If no fragment is present, this method MUST return an empty string.
     *
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URI fragment.
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }




    public function withScheme(string $scheme): UriInterface
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $this->userInfo = $this->userInfo($user, $password ?? '');
        return $this;
    }

    public function withHost(string $host): UriInterface
    {
        $this->host = $host;
        return $this;
    }

    public function withPort(?int $port): UriInterface
    {
        $this->port = $port;
        return $this;
    }

    public function withPath(string $path): UriInterface
    {

        $this->path = $path;

        return $this;
    }

    public function withQuery(string $query): UriInterface
    {

        $this->query = $query;

        return $this;
    }

    public function withFragment(string $fragment): UriInterface
    {

        $this->fragment = $fragment;

        return $this;
    }

    public function __toString(): string
    {
        if ($this->composed === null) {
            $this->composed = self::composeComponents(
                $this->scheme,
                $this->getAuthority(),
                $this->path,
                $this->query,
                $this->fragment
            );
        }

        return $this->composed;
    }

    public function jsonSerialize(): mixed
    {
        return $this->__toString();
    }

    public function full(): mixed
    {
        return $this->__toString();
    }


    public static function composeComponents(?string $scheme, ?string $authority, string $path, ?string $query, ?string $fragment): string
    {
        $uri = '';

        // weak type checks to also accept null until we can add scalar type hints
        if ($scheme != '') {
            $uri .= $scheme.':';
        }

        if ($authority != '' || $scheme === 'file') {
            $uri .= '//'.$authority;
        }

        if ($authority != '' && $path != '' && $path[0] != '/') {
            $path = '/'.$path;
        }

        $uri .= $path;

        if ($query != '') {
            $uri .= '?'.$query;
        }

        if ($fragment != '') {
            $uri .= '#'.$fragment;
        }

        return $uri;
    }

}