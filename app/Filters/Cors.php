<?php

namespace App\Filters;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

        if ($request->getMethod() === 'options') {
            $response = service('response');
            return $response->setStatusCode(ResponseInterface::HTTP_OK)
                            ->setBody('OK');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
