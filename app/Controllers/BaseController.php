<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
     public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        // $this->session = service('session'); // kalau mau pakai session
    }

    /**
     * Helper untuk ambil input dari request (JSON / raw / POST).
     */
    protected function input(): array
    {
        // 1. Coba baca JSON (kayak dari fetch body)
        $json = $this->request->getJSON(true);
        if (!empty($json)) {
            return $json;
        }

        // 2. Coba raw input (PUT/PATCH form-encoded)
        $raw = $this->request->getRawInput();
        if (!empty($raw)) {
            return $raw;
        }

        // 3. Fallback ke POST biasa (form)
        return $this->request->getPost();
    }
}
