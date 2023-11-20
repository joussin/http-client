<?php

use Joussin\Component\HttpClient\HttpDefinitions\HttpMessage;

require __DIR__ . '/vendor/autoload.php';




$url = 'https://jsonplaceholder.typicode.com';

$endpoints = [
    [
        'GET', '/posts', [], []
    ],
        [
        'GET', '/posts/1', [], []
    ],
        [
        'GET', '/posts/1/comments', [], []
    ],
        [
        'GET', '/comments', ['postId'=>1], []
    ],
         [
        'POST', '/posts', [], [            'title'=> 'foo',
                                           'body'=> 'bar',
                                           'userId'=> 1,]
    ],
        [
        'PUT', '/posts/1', [], [
            'id'=> 1,
            'title'=> 'foo',
                                'body'=> 'bar',
                                'userId'=> 1]
    ],
        [
        'PATCH', '/posts/1', [], ['title'=> 'foo']
    ],
        [
        'DELETE', '/posts/1', [], []
    ],

];

$client = new \Joussin\Component\HttpClient\Psr18\Client($url);

$endpoint = $endpoints[0];


$method = $endpoint[0];
$uri = $endpoint[1];
$query = $endpoint[2];
$body = $endpoint[3];


$response = $client->send($method, $uri, [
    HttpMessage::HEADERS => [
        'Content-Type' => 'application/json' //'application/json; charset=UTF-8'
    ],
    HttpMessage::QUERY => $query,
    HttpMessage::BODY => [
        HttpMessage::JSON => $body
    ]
]);

$content = json_decode($response->getBody()->getContents(), true);

dd(
    '$response', $response, $content
);





