<?php

declare(strict_types=1);

use App\Application\Actions\Lead\CreateLeadAction;
use App\Application\Actions\Lead\ListLeadsAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.+}', function ($request, $response) {
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/leads', function (Group $group) {
        $group->post('', CreateLeadAction::class);
        $group->get('', ListLeadsAction::class);
    });
};
