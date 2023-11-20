<?php

namespace Joussin\Component\HttpClient\HttpDefinitions;

class HttpMessage
{
    /**
     * headers: (array) Associative array of HTTP headers. Each value MUST be
     * a string or array of strings.
     */
    public const HEADERS = 'headers';

    /**
     * query: (array|string) Associative array of query string values to add
     * to the request. This option uses PHP's http_build_query() to create
     * the string representation. Pass a string value if you need more
     * control than what this method provides
     */
    public const QUERY = 'query';







    /**
     * body: (resource|string|null|int|float|StreamInterface|callable|\Iterator)
     * Body to send in the request.
     */
    public const BODY = 'body';

    /**
     * json: (mixed) Adds JSON data to a request. The provided value is JSON
     * encoded and a Content-Type header of application/json will be added to
     * the request if no Content-Type header is already present.
     */
    public const JSON = 'json';
    /**
     * form_params: (array) Associative array of form field names to values
     * where each value is a string or array of strings. Sets the Content-Type
     * header to application/x-www-form-urlencoded when no Content-Type header
     * is already present.
     */
    public const FORM_PARAMS = 'form_params';

    /**
     * multipart: (array) Array of associative arrays, each containing a
     * required "name" key mapping to the form field, name, a required
     * "contents" key mapping to a StreamInterface|resource|string, an
     * optional "headers" associative array of custom headers, and an
     * optional "filename" key mapping to a string to send as the filename in
     * the part. If no "filename" key is present, then no "filename" attribute
     * will be added to the part.
     */
    public const MULTIPART = 'multipart';



}