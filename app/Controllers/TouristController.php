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
        $raw = $request->getParsedBody();
        $tourist = Tourist::create([
            'raw' => $raw
        ]);
        return $this->json($response, ['id' => $tourist->id]);
    }
    public function create_by_wps(Request $request, Response $response, $args): Response
    {
        $response = $this->create($request, $response, $args);
        return $this->json($response, ['bind_code' => '20240420223823449467314']);
    }
    public function verify_by_wps(Request $request, Response $response, $args): Response
    {
        return $this->json($response, ['bind_code' => '20240420223823449467314']);
    }
}
