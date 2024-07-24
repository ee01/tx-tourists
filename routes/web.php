<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\TouristController;

return static function (Slim\App $app): void {
    $app->get('/', [HomeController::class, 'index'])->setName('home.index');
    $app->get('/about', [AboutController::class, 'index'])->setName('about.index');
    $app->get('/contact', [ContactController::class, 'index'])->setName('contact.index');
    $app->get('/tourists', [TouristController::class, 'index']);
    $app->get('/tourists/{id:[0-9]+}', [TouristController::class, 'get']);
    $app->post('/tourists', [TouristController::class, 'create']);
    $app->post('/tourists/bywps', [TouristController::class, 'create_by_wps']);
};
