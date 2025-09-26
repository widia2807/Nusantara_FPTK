<?php

namespace App\Filters;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // asal frontend kamu, misal http://localhost:3000
        $origin = $request->getHeaderLine('Origin');
        $allowedOrigins = [
            'http://localhost:3000',
            'http://localhost', 
            'http://127.0.0.1'
        ];

        if (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
        }

        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

        if ($request->getMethod() === 'options') {
            $response = service('response');
            return $response->setStatusCode(ResponseInterface::HTTP_OK)
                            ->setBody('OK');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
