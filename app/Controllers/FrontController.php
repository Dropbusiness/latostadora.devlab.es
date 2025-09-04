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
abstract class FrontController extends Controller
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
		$this->data['isLoggedIn'] = isset($this->session->isLoggedIn)?$this->session->isLoggedIn:false;
		$this->data['currentTheme'] = 'default';
		$this->data['config_theme']='/themes/'.$this->data['currentTheme'];
		$this->data['meta_url']=base_url();

		$this->data['meta_title']='laTostadora: Camisetas personalizadas y regalos orginales';
		$this->data['meta_keywords']='';
		$this->data['meta_description']='Crea ahora tus propias camisetas y otros productos o elige entre un catálogo de miles de diseños originales de artistas independientes. Para ti o para regalar.';
		$this->data['page_title']='laTostadora';
		$this->data['meta_image']=$this->data['meta_url'].$this->data['config_theme'].'/assets/images/logo/logo.svg';

		$this->data['page_class']='';
		$this->data['page_name']='';
		$this->data['theme_id']='';
		$this->data['breadcrumb_title']='Inicio';
		$this->data['breadcrumb'] = [
			"Inicio" => "/"
		];

		$this->db = \Config\Database::connect();
		$lang=$this->request->getLocale();
		$lang_default=$this->request->getDefaultLocale();
		$this->data['lang_default'] = $lang_default;
		$this->data['lang'] = $lang;
		$languages = new \App\Models\LanguageModel();
		$this->data['languages'] = $languages->languages();
		//print("<pre>");print_r($this->data['languages']);exit;
		$this->data['lang_id'] = isset($this->data['languages']['codes'][$lang]['id'])?$this->data['languages']['codes'][$lang]['id']:1;
    }
}
