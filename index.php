<?php
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

date_default_timezone_set('Asia/Kuala_Lumpur');

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

function getRequestHeaders($request)
{
    $output = "";

    foreach ($request->getHeaders() as $key => $values) {
        $output .= "${key} : " . implode(",", $values) . "\n";
    }

    return $output;
}

function getRequestBody($request) {
    if($request->getParsedBody() === null) return;

    $output = "";

    foreach ($request->getParsedBody() as $key => $value) {
        $output .= "${key} => ${value}\n";
    }

    return $output;
}

$app->any('/', function (Request $request, Response $response, array $args) {

    $fp = fopen('log.txt', 'a');

    $datetime = date("d-m-Y h:i:sA", time());

    $method = $request->getMethod();

    $url = $request->getUri();

    $headers = getRequestHeaders($request);

    $body = getRequestBody($request);

    $data = implode("\n", [
        "${method} ${url} [${datetime}]\n\n",
        "HEADERS:\n",
        $headers,
        "BODY:\n",
        $body
    ]) . "\n========================\n\n";

    fwrite($fp, $data);

    return "<pre>${data}</pre>";
});

fclose($fp);

$app->run();