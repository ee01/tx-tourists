<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Tourist;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class TouristController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        // return $this->render($response, 'home/index.twig');
        return $this->json($response, Tourist::find(1)->toArray());
        return $response;
    }

    public function get(Request $request, Response $response, $args): Response
    {
        // return $this->render($response, 'home/index.twig');
        return $this->json($response, Tourist::find($args['id']));
        return $response;
    }

    public function create(Request $request, Response $response, $args): Response
    {
        // return $this->render($response, 'home/index.twig');
        $raw = $request->getParsedBody();
        $tourist = Tourist::create([
            'raw' => $raw
        ]);
        return $this->json($response, ['id' => $tourist->id]);
    }
}
