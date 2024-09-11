<?php

declare(strict_types=1);

use App\Middleware\StartSession;
use Medz\Cors\PSR\CorsMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return static function (Slim\App $app): void {
    $app->addBodyParsingMiddleware();

    $app->addRoutingMiddleware();

    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));

    $app->add(new StartSession());

    // 添加CORS中间件
    $container = $app->getContainer();
    $settings = $container->get('settings');
    $app->add(new CorsMiddleware($settings['cors']));
};
