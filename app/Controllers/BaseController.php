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
     * @var array
     */
    protected $helpers = ['url', 'form', 'security', 'text', 'html', 'image'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();

        // Debug login status and session data for troubleshooting
        $this->logSessionDebug();

        // Load security helper for xss filtering
        helper('security');
    }

    /**
     * Log session data for debugging purposes
     */
    protected function logSessionDebug()
    {
        // Log session ID and status
        log_message('debug', 'Session ID: ' . session_id());
        log_message('debug', 'Logged in status: ' . (session()->get('logged_in') ? 'true' : 'false'));

        // Log user data if logged in
        if (session()->get('logged_in')) {
            log_message('debug', 'Session user data: ' . json_encode([
                'user_id' => session()->get('user_id'),
                'username' => session()->get('username'),
                'name' => session()->get('name'),
                'role' => session()->get('role'),
                'kdpelanggan' => session()->get('kdpelanggan')
            ]));
        }
    }
}
