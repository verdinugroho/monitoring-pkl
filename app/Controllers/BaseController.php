<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->helpers = ['form', 'url'];
        $this->session = service('session');
    }

    protected function enforceLogin()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        return null;
    }
}
