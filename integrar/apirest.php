<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/cities/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) {

    return $response;
});

$app->get('/cities', function (ServerRequestInterface $request, ResponseInterface $response) {
 
    return $response;
});

$app->post('/cities', function (ServerRequestInterface $request, ResponseInterface $response) {

    return $response;
});

$app->put('/cities/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) {

    return $response;
});

$app->delete('/cities/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) {

    return $response;
});

$app->run();

?>
