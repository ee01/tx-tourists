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
    $app->get('/tourists', [ContactController::class, 'index']);
    $app->get('/tourists/{id}', [TouristController::class, 'get']);
    $app->post('/tourists', [ContactController::class, 'create']);
};
