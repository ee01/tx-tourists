<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;

/** @var Psr\Container\ContainerInterface $container */
$container = require __DIR__ . '/container.php';

AppFactory::setContainer($container);

$app = AppFactory::create();
(require __DIR__ . '/eloquent.php')($app);

return $app;
