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
    protected $helpers = ['form', 'general','cups','text','html','cookie'];

	protected $currentUser = null;
	protected $auth = null;
	protected $data = [];
	protected $session = null;
	protected $db;
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        //--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->session = \Config\Services::session();
		$this->data['session'] = $this->session;
		$this->auth = new \IonAuth\Libraries\IonAuth();
		$this->currentUser = $this->auth->user()->row();
		$this->data['auth'] = $this->auth;
		$this->data['currentUser'] = $this->currentUser;
		$this->data['currentTheme'] = 'default';
		$this->data['config_theme']='/themes/'.$this->data['currentTheme'];
		$this->db = \Config\Database::connect();

		$lang=isset($this->session->lang)?$this->session->lang:$this->request->getLocale();
		$this->request->setLocale($lang);
		$this->data['lang'] = $lang;
		$languages = new \App\Models\LanguageModel();
		$this->data['languages'] = $languages->languages();
		$this->data['lang_id'] = isset($this->data['languages']['codes'][$lang]['id'])?$this->data['languages']['codes'][$lang]['id']:null;
		//$language = \Config\Services::language();
		//$language->setLocale($lang);
    }
}
