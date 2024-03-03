<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ContactController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        return $this->render($response, 'contact/index.twig');
    }
}
