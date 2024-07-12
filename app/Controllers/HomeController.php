<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Tourist;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        // return $this->render($response, 'home/index.twig');
        return $this->json($response, Tourist::find(1)->toArray());
        return $response;
    }
}
