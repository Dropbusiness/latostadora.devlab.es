<?php namespace App\Controllers\Front;
use App\Controllers\FrontController;
use App\Models\LanguageModel;
use App\Models\ConfigurationModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\BrandModel;
use App\Models\PageModel;
use App\Models\ProductImageModel;
use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\OrderdetailsModel;
use App\Models\EventsModel;
use App\Models\ToursModel;
use Adyen\AdyenException;
use Adyen\Client;
use Adyen\Environment;
use Adyen\Service\Checkout;
use Adyen\Service\CheckoutUtility;
class Web extends FrontController
{
	protected $languageModel;
	protected $configurationModel;
	protected $productModel;
    protected $categoryModel;
    protected $brandModel;
	protected $pageModel;
	protected $productImageModel;
	protected $customerModel;
	protected $orderModel;
	protected $orderdetailsModel;
	protected $eventsModel;
	protected $toursModel;
	public function __construct(){

		$this->configurationModel = new ConfigurationModel();
		$this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->brandModel = new BrandModel();
		$this->pageModel = new PageModel();
		$this->productImageModel = new ProductImageModel();
		$this->customerModel = new CustomerModel();
		$this->orderModel = new OrderModel();
		$this->orderdetailsModel = new OrderdetailsModel();
		$this->languageModel = new LanguageModel();
		$this->eventsModel = new EventsModel();
		$this->toursModel = new ToursModel();
		$lang=service('request')->getLocale();
		$this->data['languages'] = $this->languageModel->languages();
		$lang_id = isset($this->data['languages']['codes'][$lang]['id'])?$this->data['languages']['codes'][$lang]['id']:1;
		$cacheHelper = new \App\Libraries\CacheHelper($lang_id);
    	$this->data = $cacheHelper->getCachedData();
	}
	public function adyenprueba()
{
	//email
	$this->orderModel->sendemailorder($order_id=64);
	exit;
	//validar session
	$order=$this->orderModel->where(['id'=>66])->first();	
	$payment_session=json_decode($order['payment_session'],true);
	$environment = env('ADYEN_ENVIRONMENT') == 'live' ? \Adyen\Environment::LIVE : \Adyen\Environment::TEST;
	$adyenPrefix = env('ADYEN_PREFIX');
	$baseUrl = $environment == \Adyen\Environment::LIVE 
		? "https://{$adyenPrefix}-checkout-live.adyenpayments.com/checkout/v71"
		: "https://checkout-test.adyen.com/v71";
	$sessionId = $payment_session['datasession']['sessionId']; 
	$sessionResult = $payment_session['sessionResult'];
	$endpoint = $baseUrl . "/sessions/" . $sessionId . "?sessionResult=" . urlencode($sessionResult);
	$client = \Config\Services::curlrequest();
	$response = $client->request('GET', $endpoint, [
		'headers' => [
			'X-API-Key' => env('ADYEN_API_KEY'),
			'Content-Type' => 'application/json'
		],
		'http_errors' => false
	]);
	if ($response->getStatusCode() !== 200) {
		throw new \Exception('Error en la respuesta: ' . $response->getBody());
	}
	$result = json_decode($response->getBody(), true);
	print("<pre>".print_r($result,true)."</pre>");exit;

	#crear pedido en adyen
	$order = $this->orderModel->getcart($order_id=79, true);
    // Cargar configuración desde el .env
    $merchantAccount = env('ADYEN_MERCHANT_ACCOUNT');
    $apiKey = env('ADYEN_API_KEY');
    $environment = env('ADYEN_ENVIRONMENT')=='live'?\Adyen\Environment::LIVE:\Adyen\Environment::TEST;
	
    try {
        // Configurar el cliente de Adyen
        $client = new \Adyen\Client();
        $client->setXApiKey($apiKey);
        $client->setEnvironment($environment);
        $client->setTimeout(30);
        
        // Servicio de métodos de pago
        $service = new \Adyen\Service\Checkout\PaymentsApi($client);

        // Create PaymentMethod object with correct card details structure
        $paymentMethod = new \Adyen\Model\Checkout\CheckoutPaymentMethod();
        $paymentMethod
            ->setType("scheme")
            ->setEncryptedCardNumber("test_4111111111111111")  // Changed from encryptedBankAccountNumber
            ->setEncryptedExpiryMonth("test_03")
            ->setEncryptedExpiryYear("test_2030")
            ->setEncryptedSecurityCode("test_737");

        // Creating Amount Object
        $amount = new \Adyen\Model\Checkout\Amount();
        $amount
            ->setValue(1500)
            ->setCurrency("EUR");

        // Create the actual Payments Request
        $paymentRequest = new \Adyen\Model\Checkout\PaymentRequest();
        $paymentRequest
            ->setMerchantAccount($merchantAccount)
            ->setPaymentMethod($paymentMethod)
            ->setAmount($amount)
            ->setReference("payment-test-" . time())  // Added timestamp for uniqueness
            ->setReturnUrl("https://your-company.com/...")
            ->setChannel("Web");  // Specify the channel

        $response = $service->payments($paymentRequest);
        
        // Log the response for debugging in production
        log_message('debug', 'Adyen Response: ' . json_encode($response));
        
        return $this->response->setJSON($response);
    } catch (\Exception $e) {
        // Enhanced error handling
        log_message('error', 'Adyen Error: ' . $e->getMessage());
        return $this->response
            ->setStatusCode(500)
            ->setJSON([
                'error' => $e->getMessage(),
                'status' => 'error',
                'timestamp' => time()
            ]);
    }
}
public function adyenhandlewebhook()
{
    try {
        // Obtener el contenido del webhook
        $notificationRequest = $this->request->getJSON(true);
        // Logging inicial
        file_put_contents(
            './uploads/log/webservice.txt',
            date('Y-m-d H:i:s') . ') [adyen_adyenhandlewebhook]: ' . json_encode($notificationRequest) . PHP_EOL,
            FILE_APPEND
        );
        // Verificar que sea una notificación AUTHORISATION
        if (isset($notificationRequest['notificationItems'][0]['NotificationRequestItem'])) {
            $notification = $notificationRequest['notificationItems'][0]['NotificationRequestItem'];
            if ($notification['eventCode'] === 'AUTHORISATION' && $notification['success']=='true') {
                $order_id_uuid=$notification['merchantReference'];
                if ($order_id_uuid && $order_id_uuid!=''){
					$payment_reference=$notification['pspReference'];
					$iorder=$this->orderModel->where(['id_uuid'=>$order_id_uuid])->first();
					if(isset($iorder['id']) && $iorder['order_status']==3 && $iorder['payment_data']==''){
						$this->orderModel->where(['id'=>$iorder['id']])->set([
							'payment_reference'=>$payment_reference,
							'payment_data'=>json_encode($notification)
						])->update();
					}elseif(isset($iorder['id']) && $iorder['order_status']==1 && $iorder['payment_data']==''){
						$this->orderModel->where(['id'=>$iorder['id']])->set([
							'payment_reference'=>$payment_reference,
							'payment_data'=>json_encode($notification)
						])->update();
						$this->orderModel->orderprocess($iorder['id']);
					}elseif(isset($iorder['id'])){
						#guardamos en caso de que el pedido ya exista
						$this->orderModel->where(['id'=>$iorder['id']])->set([
							'payment_datab'=>json_encode($notification)
						])->update();
					}
					

                }
            }else{
				#guardamos en caso de que el pedido no exista
				$order_id_uuid=$notification['merchantReference'];
				if ($order_id_uuid && $order_id_uuid!=''){
					$this->orderModel->where(['id_uuid'=>$order_id_uuid])->set([
						'payment_datab'=>json_encode($notification)
					])->update();
				}
			}
        }
        // Siempre devolver [accepted] a Adyen
        return $this->response->setHeader('Content-Type', 'text/plain')
                            ->setBody('[accepted]');
        
    } catch (\Exception $e) {
        log_message('error', 'Adyen Webhook Error: ' . $e->getMessage());
        // Aún así devolvemos [accepted] para evitar reintentos innecesarios
        return $this->response->setHeader('Content-Type', 'text/plain')
                            ->setBody('[accepted]');
    }
}
public function adyen()
{
    try {
        // Obtener el ID del carrito actual
        $order_id = $this->getsetidcart(false);
        if (!$order_id) {
            return $this->response->setJSON([
                'error' => 'No hay un carrito activo'
            ]);
        }
		
        // Obtener detalles del pedido
        $order = $this->orderModel->getcart($order_id, true);
        $amountInCents = (int)($order['cart_total'] * 100);
		# Obtener detalles de customer
		$customer = $this->customerModel->where('id',$order['cart']['customer_id'])->first();
		
		// Crear un código de sesión único
		$session_code =  uniqid();

		$environment = env('ADYEN_ENVIRONMENT')=='live'?\Adyen\Environment::LIVE:\Adyen\Environment::TEST;
        // Configurar el cliente
        $client = new \Adyen\Client();
        $client->setXApiKey(env('ADYEN_API_KEY'));
		// Configurar el entorno con el prefijo para LIVE
		if (env('ADYEN_ENVIRONMENT')== 'live') {
			$client->setEnvironment(\Adyen\Environment::LIVE, env('ADYEN_PREFIX'));
		} else {
			$client->setEnvironment(\Adyen\Environment::TEST);
		}

		// Crear los line items para PayPal
        $lineItems = [];
        // Aquí debes iterar sobre los productos del carrito y crear los line items
        foreach ($order['cart_list'] as $product) {
            $lineItem = new \Adyen\Model\Checkout\LineItem();
            $lineItem
                ->setQuantity($product['quantity'])
                ->setItemCategory("DIGITAL_GOODS")
                ->setDescription($product['name'])
                //->setAmountExcludingTax((int)($product['price'] * 100))
                //->setTaxAmount((int)($product['price'] * 100))
				->setSku($product['sku']);
            $lineItems[] = $lineItem;
        }
        // Crear el objeto Amount
        $amount = new \Adyen\Model\Checkout\Amount();
        $amount->setCurrency("EUR")
               ->setValue($amountInCents);
		
        // Crear la solicitud de sesión
        $createCheckoutSessionRequest = new \Adyen\Model\Checkout\CreateCheckoutSessionRequest();
        $createCheckoutSessionRequest
            //->setReference("order-{$order_id}-" . time())
            ->setReference($order['cart']['id_uuid'])
            ->setAmount($amount)
            // Modificamos el returnUrl para incluir una ruta específica de verificación
            ->setReturnUrl(base_url_locale("adyenpaymentverify/{$session_code}"))
            ->setMerchantAccount(env('ADYEN_MERCHANT_ACCOUNT'))
			//agregar pago paypal
			->setAllowedPaymentMethods(['scheme', 'paypal']) // Añadir PayPal aquí
            ->setCountryCode("ES")
            ->setChannel("Web")
            ->setShopperLocale("es-ES")
			->setLineItems($lineItems) // Añadir line items para PayPal
            // Datos de envío para PayPal Seller Protection
            ->setDeliveryAddress([
				'street' => $customer['address'],
                'houseNumberOrName' => '1', // Añadir el número de casa
                'postalCode' => $customer['cp'],
                'city' => $customer['city'],
				'country' => 'ES',
                //'country' => $customer['country'],
            ])
            ->setShopperName([
                'firstName' => $customer['firstname'],
                'lastName' => $customer['lastname']
            ])
            ->setShopperEmail($customer ['email']); // Requerido para PayPal
        // Opciones de la solicitud
        $requestOptions = [
            'idempotencyKey' => uniqid('session_', true)
        ];

        // Crear el servicio y enviar la solicitud
        $service = new \Adyen\Service\Checkout\PaymentsApi($client);
        $response = $service->sessions($createCheckoutSessionRequest, $requestOptions);
		// Guardamos información de la sesión para verificación posterior
		session()->set('adyen_payment_session', [
			'session_code' => $session_code,
			'order_id' => $order_id,
			'amount' => $amountInCents
		]);
		$params=[
            'session' => [
                'id' => $response['id'],
                'sessionData' => $response['sessionData']
            ],
            'clientKey' => env('ADYEN_CLIENT_KEY'),
			'session_code' => $session_code,
			'amount' => $amountInCents,
			'returnUrl' => base_url_locale("adyenpaymentverify/{$session_code}")
        ];
		// regisrar en log
		file_put_contents('./uploads/log/webservice.txt',date('Y-m-d H:i:s').') [adyen:create session]:'.json_encode($params).PHP_EOL, FILE_APPEND);
		return $this->response->setJSON($params);

    } catch (\Exception $e) {
        log_message('error', 'Adyen Error: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'error' => $e->getMessage()
        ]);
    }
}
/*
https://checkout-test.adyen.com/v71/sessions/{sessionId}
return:
{
  "id": "CS12345678",
  "status": "completed"
}
   * **completed** – The shopper completed the payment. This means that the payment was authorized.
     * **paymentPending** – The shopper is in the process of making the payment. This applies to payment methods with an asynchronous flow.
     * **refused** – The session has been refused, due to too many refused payment attempts. Shoppers can no longer complete the payment with this session.
     * **canceled** – The shopper canceled the payment.
     * **active** – The session is still active and can be paid.
     * **expired** – The session expired (default: 1 hour after session creation). Shoppers can no longer complete the payment with this session.
*/
public function adyenpaymentverify()
{
    try {
        $requestData = $this->request->getJSON(true);
        
        // Validar datos requeridos
        if (!isset($requestData['order_id'], $requestData['resultCode'], $requestData['datasession'])) {
            throw new \Exception('Datos de verificación incompletos');
        }

        $sessionId = $requestData['datasession']['sessionId'] ?? null;
        $sessionResult = $requestData['sessionResult'] ?? null;
        
        if (!$sessionId || !$sessionResult) {
            throw new \Exception('sessionId y sessionResult son requeridos');
        }
		//verificamos si la session es correcta
		$sessionData = session()->get('adyen_payment_session');
		if (!$sessionData || $sessionData['session_code'] !== $requestData['datasession']['session_code']) {
			throw new \Exception('Sesión no válida');
		}
        //guardar en log
		file_put_contents('./uploads/log/webservice.txt',date('Y-m-d H:i:s').') [adyen:paymentverify]:'.json_encode($requestData).PHP_EOL, FILE_APPEND);
		// guardamos en la columna payment_session de la tabla order
		$this->orderModel->where(['id'=>$requestData['order_id']])->set(['payment_session'=>json_encode($requestData)])->update();
        // Configurar cliente Adyen y URL
        $environment = env('ADYEN_ENVIRONMENT') == 'live' ? \Adyen\Environment::LIVE : \Adyen\Environment::TEST;
		$adyenPrefix = env('ADYEN_PREFIX');
        $baseUrl = $environment == \Adyen\Environment::LIVE 
            ? "https://{$adyenPrefix}-checkout-live.adyenpayments.com/checkout/v71"
            : "https://checkout-test.adyen.com/v71";
            
        // Construir endpoint
        $endpoint = $baseUrl . "/sessions/" . $sessionId . "?sessionResult=" . urlencode($sessionResult);
        
        // Usar el cliente HTTP de CI4
        $client = \Config\Services::curlrequest();
        $response = $client->request('GET', $endpoint, [
            'headers' => [
                'X-API-Key' => env('ADYEN_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'http_errors' => false
        ]);
        
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error en la respuesta: ' . $response->getBody());
        }
        
        // Procesar respuesta
        $result = json_decode($response->getBody(), true);
        
        // Validar el estado del pago
        $status = $result['status'] ?? '';
        $statusMessages = [
            'completed' => ['success' => true, 'message' => 'El pago ha sido autorizado y completado con éxito'],
            'paymentPending' => ['success' => false, 'message' => 'El pago está pendiente de procesamiento'],
            'refused' => ['success' => false, 'message' => 'El pago ha sido rechazado'],
            'canceled' => ['success' => false, 'message' => 'El pago ha sido cancelado'],
            'active' => ['success' => false, 'message' => 'La sesión está activa'],
            'expired' => ['success' => false, 'message' => 'La sesión ha expirado']
        ];
        
        $statusInfo = $statusMessages[$status] ?? [
            'success' => false, 
            'message' => 'Estado de pago desconocido: ' . $status
        ];
        
        // Si el pago está completado, procesar el pedido
        if ($status === 'completed') {
			//verificamos si el pedido  está confirmado
			$order=$this->orderModel->where('id',$requestData['order_id'])->first();
			if($order['order_status']==1){ #solo actuliza si el pedido no está confirmado
             	$this->orderModel->orderprocess($requestData['order_id']);
			}
        }
        
        return $this->response->setJSON([
            'success' => $statusInfo['success'],
            'status' => $statusInfo['message'],
            'resultCode' => $status,
            'rawResponse' => $result
        ]);
        
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
	public function ordercancel()
	{
		$this->data['page_class']='ordercancel';
		$this->data['page_name']='ordercancel';
		/*breadcrumb*/
		$this->data['page_title']= "Error";
		$this->data['breadcrumb_title']="Error";
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),"Error" => base_url_locale('ordercancel')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/ordercancel', $this->data);
	}

	public function index()
	{
		$this->data['page_class']='home';
		$this->data['page_name']='home';
		return view('themes/'. $this->data['currentTheme'] .'/pages/home', $this->data);
	}
	public function content($slug)
	{
		$this->data['details']=$this->pageModel->getitem('',$slug,$this->data['lang_id']);
		$this->data['meta_url']=base_url_locale('contenido/'.$this->data['details']['link_rewrite']);
		$this->data['meta_title']=$this->data['details']['meta_title'];
		$this->data['meta_keywords']=$this->data['details']['meta_keywords'];
		$this->data['meta_description']=$this->data['details']['meta_description'];
		$this->data['page_title']=$this->data['details']['name'];
		$this->data['page_class']='content';
		$this->data['page_name']='content-'.$this->data['details']['id'];
		$this->data['breadcrumb_title']=$this->data['details']['name'];
		$this->data['breadcrumb'] = [
			front_translate('General', 'home') => base_url_locale(''),
            $this->data['details']['name'] => ""
		];
		return view('themes/'. $this->data['currentTheme'] .'/pages/content', $this->data);
	}
	
	public function product($slug)
	{
		$event_id=(int)$this->request->getVar('event');
		$this->data['details']=$this->productModel->getitem('',$slug,$this->data['lang_id']);
		if(!isset($this->data['details']['id']))
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

		if($event_id>0){
			$this->data['event'] =$this->eventsModel->getfront($event_id,$artist_slug='',$tour_slug='',$event_slug='',$event_date='',$this->data['lang_id']);
			$this->data['events'] =$this->eventsModel->getlistfront($this->data['event']['artist_slug'],$this->data['event']['tour_slug'],$this->data['lang_id']);
			$this->data['recommendation'] =$this->productModel->getproducts(0,0,$this->data['lang_id'],'','','','',16,$event_id);
			$this->data['theme_id']='theme_'.$this->data['event']['tour_id'];
		}

		$this->data['combinaciones']=$this->productModel->iattributes($this->data['details']['id'],$this->data['lang_id']);
		$this->data['photos']=$this->productImageModel->where('product_id',$this->data['details']['id'])->orderBy('cover DESC, position ASC')->find();
		$this->data['meta_url']=base_url_locale('producto/'.$this->data['details']['link_rewrite']);
		$this->data['meta_title']=$this->data['details']['meta_title'];
		$this->data['meta_keywords']=$this->data['details']['meta_keywords'];
		$this->data['meta_description']=$this->data['details']['meta_description'];
		$this->data['meta_image']=base_url('/uploads/products/large/'.$this->data['details']['img']);
		$this->data['page_title']=$this->data['details']['name'];
		//$this->data['page_class']='catalogos';
		//$this->data['page_name']='product';
		
		/*breadcrumb*/
		
		$this->data['breadcrumb_title']=$this->data['details']['name'];
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale('')];

		$this->data['page_class']='product_page';
		$this->data['page_name']='product';
		return view('themes/'. $this->data['currentTheme'] .'/pages/product', $this->data);
	}
	public function tour($artist_slug,$tour_slug)
	{
		$this->data['tour'] =$this->toursModel->getfront($artist_slug,$tour_slug,$this->data['lang_id']);

		if (!$this->data['tour'])
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

		$this->data['events'] =$this->eventsModel->getlistfront($artist_slug,$tour_slug,$this->data['lang_id']);
		

		$this->data['page_class']='tour';
		$this->data['page_name']='tour';
		/*breadcrumb*/
		$this->data['theme_id']='theme_'.$this->data['tour']['id'];
		$this->data['page_title']= $this->data['tour']['meta_title'];
		$this->data['meta_title']=$this->data['tour']['meta_title'];
		$this->data['meta_keywords']=$this->data['tour']['meta_title'];
		//limpiar descripcion de html
		$this->data['meta_description']=trim(strip_tags($this->data['tour']['meta_description']));

		$this->data['breadcrumb_title']="Tour";
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),"Catalogo" => base_url_locale('catalogo')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/tour', $this->data);
	}

	public function event($artist_slug,$tour_slug,$event_slug,$event_date)
	{
		$this->data['event'] =$this->eventsModel->getfront($event_id='',$artist_slug,$tour_slug,$event_slug,$event_date,$this->data['lang_id']);
		
		if (!$this->data['event'])
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		$this->data['events'] =$this->eventsModel->getlistfront($artist_slug,$tour_slug,$this->data['lang_id']);
		$this->data['recommendation'] =$this->productModel->getproducts(0,0,$this->data['lang_id'],'','','','',16,$this->data['event']['id']);
		$this->data['page_class']='catalogo';
		$this->data['page_name']='catalogo';
		/*breadcrumb*/
		$this->data['theme_id']='theme_'.$this->data['event']['tour_id'];
		$this->data['page_title']= $this->data['event']['artist_name'].' '.$this->data['event']['tour_name'];
		$this->data['meta_title']=$this->data['event']['artist_name'].' '.$this->data['event']['tour_name'];
		$this->data['meta_keywords']=$this->data['event']['artist_name'].' '.$this->data['event']['tour_name'];
		//limpiar descripcion de html
		$this->data['meta_description']=trim(strip_tags($this->data['event']['tour_description']));

		$this->data['breadcrumb_title']="Catalogo";
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),"Catalogo" => base_url_locale('catalogo')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/catalogo', $this->data);
	}

	
	public function contacto()
	{
		$this->data['page_class']='contacto';
		$this->data['page_name']='contacto';
		/*breadcrumb*/
		$this->data['page_title']=front_translate('General','contact');
		$this->data['breadcrumb_title']=front_translate('General','contact');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','contact') => base_url_locale('contacto')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/contact', $this->data);
	}
	public function addcontacto() {
		helper(['form', 'url']);
		/*$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; 
		$recaptcha_secret = getenv('recaptcha.secretkey'); 
		$recaptcha_response = $_POST['recaptcha_response']; 
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
		$recaptcha = json_decode($recaptcha); 
		if(isset($recaptcha->score) && $recaptcha->score >= 0.7){*/
		$frmkey=$this->request->getVar('frmkey');
			if($frmkey==date('Ymd')){
				$validation = [
					'name' => 'required|max_length[200]',
					'message' => 'required|max_length[9000]',
					'phone' => 'required|max_length[20]|min_length[6]',
					'email' => 'required|max_length[250]|valid_email',
				];
				$contactModel = new \App\Models\ContactModel();
				if (!$this->validate($validation))
				{
					$data['validation'] = $this->validator;
					return view('themes/'. $this->data['currentTheme'] .'/pages/contact', $this->data);
				} else {
					$email=$this->request->getVar('email');
					$publicidad=(int)$this->request->getVar('publicidad');
					$contactModel->save([
						'first_name' => $this->request->getVar('name'),
						'last_name' => '',
						'email'  => $email,
						'phone'  => $this->request->getVar('phone'),
						'message'  => $this->request->getVar('message'),
						'customer_id'  => (isset($this->session->userData['id'])?$this->session->userData['id']:''),
						'optin' => $publicidad,
						'ip' => $this->request->getIPAddress(),
						'ctype'  => 1,/*1:contacto*/
						'status' => 1,
					]);
					$html='';
					$html.='<h2>Mensaje desde el formulario Contacto:</h2>';
					$html.='<p>Detalle del mensaje:</p>';
					$html.='<b>Nombre:</b>'.$this->request->getVar('name').'<br/>';
					$html.='<b>Email:</b>'.$email.'<br/>';
					$html.='<b>Teléfono:</b>'.$this->request->getVar('phone').'<br/>';
					$html.='<b>Mensaje:</b>'.nl2br($this->request->getVar('message'));
					if(isset($this->data['configuration']['WEB_CMAILCONTACT']) && $this->data['configuration']['WEB_CMAILCONTACT']!=''){
						$emails_to=explode(',',trim($this->data['configuration']['WEB_CMAILCONTACT']));
						send_email('themes/default/email/default',['message'=>$html],$emails_to,'Formulario contacto');
					}
					$emailtheme = new \App\Models\TemplateemailModel();
					$dataemail=$emailtheme->getitem(1,$this->data['lang_id']);
					send_email('themes/default/email/default',['message'=>$dataemail['description']],$email,$dataemail['subject']);
					$this->session->setFlashdata('success',$dataemail['subject']);
        			return redirect()->to(base_url_locale('contacto'));
				}
		}else{
			$this->session->setFlashdata('errors','Ha ocurrido un error. por favor intente nuevamente.');
			return redirect()->to(base_url_locale('contacto'));
		}
	  }

	
	public function signin()
	{
		if ($this->session->isLoggedIn)
			return redirect()->to('micuenta');
		$this->data['page_class']='signin';
		$this->data['page_name']='signin';
		$this->data['page_title']='Login';
		$this->data['breadcrumb_title']='Login';
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),"Login" => base_url_locale('signin')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/signin', $this->data);
	}
	public function attemptLogin()
	{
		$rules = [
			'email'	=> 'required|min_length[1]|max_length[100]',
			'password' 	=> 'required|min_length[4]|max_length[50]',
		];
		if (! $this->validate($rules)) {
			$this->session->setFlashdata('errors','Error de validación.');
			return redirect()->to(base_url_locale('signin'));
		}
		$email=$this->request->getPost('email');
		$user = $this->customerModel->where('email',$email)->first();
		if (is_null($user)){
			$this->session->setFlashdata('errors','Error, cliente no existe');
			return redirect()->to(base_url_locale('signup'));
		}elseif($user['passwd']!=md5($this->request->getPost('password'))){
			$this->session->setFlashdata('errors','Error de contraseña');
			return redirect()->to(base_url_locale('signin'));
		}
		if (!$user['status']) {
			$this->session->setFlashdata('errors','Error de autenticación, cuenta desactivada');
			return redirect()->to(base_url_locale('signin'));
		}
		$this->session->set('isLoggedIn', true);
		$this->session->set('userData', [
            'id' 			=> $user["id"],
            'company' 		=> $user["company"],
            'firstname' 	=> $user["firstname"],
            'lastname' 		=> $user["lastname"],
            'email' 		=> $user["email"],
            'cif' 	=> $user["cif"],
			'country' 	=> $user["country"],
			'city' 	=> $user["city"],
			'address' 	=> $user["address"],
        ]);
        $agent = $this->request->getUserAgent();
		$logs = new \App\Models\LogsModel();
		$userlog = [
			'date'	=> date("Y-m-d"),
			'time'	=> date("H:i:s"),
			'reference'	=> $user["id"],
			'name'	=> $user["company"],
			'ip'	=> $this->request->getIPAddress(),
			'browser'	=> $agent->getBrowser(),
			'status'	=> 'Success' 
		];
		$logs->save($userlog);
		$redirect=$this->request->getVar('pr')?$this->request->getVar('pr'):'micuenta';
		return redirect()->to(base_url_locale('micuenta'));
        //return redirect()->to($redirect);
	}
	public function signup()
	{
		if ($this->session->isLoggedIn)
			return redirect()->to('micuenta');
		$this->data['page_class']='signup';
		$this->data['page_name']='signup';
		$this->data['page_title']=front_translate('General', 'new-registration');
		$this->data['breadcrumb_title']=front_translate('General', 'new-registration');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General', 'new-registration') => base_url_locale('signup')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/signup', $this->data);
	}
	public function signupadd()
	{

		$rules = [
			'firstname' => 'required|min_length[2]|max_length[100]',
			'lastname' => 'required|min_length[2]|max_length[100]',
			'country' => 'required|min_length[1]|max_length[100]',
			'city' => 'required|min_length[1]|max_length[100]',
			'email' => 'required|max_length[250]|valid_email',
			'passwd' => 'required|max_length[100]',
			'address' => 'required|min_length[1]|max_length[250]',
			'cp' => 'required|min_length[1]|max_length[100]',
			'phone' => 'required|max_length[20]|min_length[6]'

		];
		
		if (!$this->validate($rules)) {
			$this->session->setFlashdata('errors','Error de validación.');
			return redirect()->to(base_url_locale('signup'));
		}
		$email=$this->request->getPost('email');
		$user = $this->customerModel->where('email',$email)->first();
		if (!empty($user)){
			$this->session->setFlashdata('errors','Error, cliente ya existe, tiene que recuperar contraseña');
			return redirect()->to(base_url_locale('signup'));
		}

		$params = [
			'company' => $this->request->getVar('company'),
            'country' => $this->request->getVar('country'),
            'city' => $this->request->getVar('city'),
			'cp' => $this->request->getVar('cp'),
			'phone' => $this->request->getVar('phone'),
            'address' => $this->request->getVar('address'),
            'firstname' => $this->request->getVar('firstname'),
            'lastname' => $this->request->getVar('lastname'),
            'email' => $this->request->getVar('email'),
			'optin' => $this->request->getPost('publicidad') ? 1 : 0 ,
            'passwd' => md5($this->request->getVar('passwd')),
			'password_token' => md5(uniqid()),
			'status' => 1,
        ];
        $this->db->transStart();
        $this->customerModel->save($params);
        $user = $this->customerModel->find($this->db->insertID());
        $this->db->transComplete();

		$emailtheme = new \App\Models\TemplateemailModel();
		$dataemail=$emailtheme->getitem(3,$this->data['lang_id']);
		send_email('themes/default/email/default',['message'=>$dataemail['description']],$email,$dataemail['subject']);

		$this->session->set('isLoggedIn', true);
		$this->session->set('userData', [
            'id' 			=> $user["id"],
            'company' 		=> $user["company"],
            'firstname' 	=> $user["firstname"],
            'lastname' 		=> $user["lastname"],
            'email' 		=> $user["email"],
            'phone' 	=> $user["phone"],
			'cp' 	=> $user["cp"],
			'country' 	=> $user["country"],
			'city' 	=> $user["city"],
			'address' 	=> $user["address"],
        ]);
       

		return redirect()->to(base_url_locale('micuenta'));
	}
	public function autologin($token)
	{
		$this->session->remove(['isLoggedIn', 'userData']);
		if(strlen($token)==41){
			$password_token = substr($token,0,32);
			$usercode = substr($token, -9);
			$user = $this->customerModel->where(['usercode'=>$usercode,'password_token'=>$password_token])->first();
			if (is_null($user)){
				$this->session->setFlashdata('errors','Error, posiblemente el token de acceso a expirado, vuelva a intentar');
				return redirect()->to(base_url_locale('message'));
			}
			$this->session->set('isLoggedIn', true);
			$this->session->set('userData', [
				'id' 			=> $user["id"],
				'company' 		=> $user["company"],
				'firstname' 	=> $user["firstname"],
				'lastname' 		=> $user["lastname"],
				'email' 		=> $user["email"],
				'usercode' 	=> $user["usercode"]
			]);
			$this->customerModel->where(['id'=>$user["id"]])->set(['password_token'=>md5(uniqid())])->update();
			return redirect()->to(base_url_locale('micuenta'));
		}else{
			$this->session->setFlashdata('errors','Error, el token es incorrecto, consulte con el administrador de la web');
			return redirect()->to(base_url_locale('message'));
		}
	}
	public function logout()
	{
		$this->session->remove(['isLoggedIn', 'userData']);
        return redirect()->to(base_url_locale('signin'));
	}
	public function myaccount()
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));
		$this->data['page_class']='myaccount';
		$this->data['page_name']='myaccount';
		$this->data['page_title']=front_translate('General','my-account');
		$this->data['breadcrumb_title']=front_translate('General','my-account');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','my-account') => base_url_locale('micuenta')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/myaccount', $this->data);
	}
	public function forgotPassword()
	{
		$this->data['page_class']='forgotpassword';
		$this->data['page_name']='forgotpassword';
		$this->data['page_title']=front_translate('General','forgot-password');
		$this->data['breadcrumb_title']=front_translate('General','forgot-password');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','forgot-password') => base_url_locale('recuperar-contrasena')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/forgotpassword', $this->data);
	}
	public function attemptForgotPassword()
	{
		if (! $this->validate(['email' => 'required|min_length[1]|max_length[100]|valid_email'])) {
			$this->session->setFlashdata('errors','Error al recuperar contraseña, póngase en contacto con el administrador para obtener más información');
			return redirect()->to('recuperar-contrasena');
		}
		$user = $this->customerModel->where('email', $this->request->getPost('email'))->first();
		if (is_null($user) || !$user['status']) {
			$this->session->setFlashdata('errors','Error al recuperar contraseña, póngase en contacto con el administrador para obtener más información');
			return redirect()->to('recuperar-contrasena');
		}
		// set reset hash and expiration
		$updatedUser['id'] = $user['id'];
		$password=password_generate(6);
		$updatedUser['passwd'] = md5($password);
		$this->customerModel->save($updatedUser);

		$emailtheme = new \App\Models\TemplateemailModel();
		$dataemail=$emailtheme->getitem(5,$this->data['lang_id']);
		$datadefault = array('{PASSWORD}' => trim($password));
		$msgdefault=parsehtml($dataemail['description'],array('%7B'=>'{','%7D'=>'}'));
		$msgemaildefaul=parsehtml($msgdefault,$datadefault);
	   if(send_email('themes/default/email/default',['message'=>$msgemaildefaul],$user['email'],$dataemail['subject'])){
			$this->session->setFlashdata('success','En breve recibirá un email  con la nueva contraseña');
			return redirect()->to('recuperar-contrasena');
	   }else{
			$this->session->setFlashdata('errors','Error al recuperar contraseña, póngase en contacto con el administrador para obtener más información');
			return redirect()->to('recuperar-contrasena');
	   }
	}
	public function misdatos()
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));
		$this->data['customer'] = $this->customerModel->where('id', $this->session->userData['id'])->first();
		$this->data['page_class']='misdatos';
		$this->data['page_name']='misdatos';
		$this->data['page_title']=front_translate('General','home');
		$this->data['breadcrumb_title']=front_translate('General','my-data');
		$this->data['breadcrumb'] = [front_translate('General', 'my-data') => base_url_locale(''),front_translate('General','my-account') => base_url_locale('micuenta')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/misdatos', $this->data);
	}
	public function changepassword()
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));

		$this->data['page_class']='changepassword';
		$this->data['page_name']='changepassword';
		$this->data['page_title']=front_translate('General','modify-password');
		$this->data['breadcrumb_title']=front_translate('General','modify-password');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','my-account') => base_url_locale('micuenta')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/changepassword', $this->data);
	}
	public function attemptchangepassword()
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));
		$rules = [
			'password' => 'required|min_length[4]',
			'password_confirm' => 'matches[password]'
		];
		if (! $this->validate($rules)) {
			$this->session->setFlashdata('errors','Comprueba la contraseña y vuelve a intentarlo');
			return redirect()->to(base_url_locale('change-password'));
        }
		$updatedUser['id'] = $this->session->userData['id'];
		$updatedUser['passwd'] = md5(trim($this->request->getPost('password')));
		$this->customerModel->save($updatedUser);
		$this->session->setFlashdata('success','Tu contraseña se ha modificado correctamente');
		return redirect()->to(base_url_locale('change-password'));

	}

	public function changedato()
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));

		$customer_id=$this->session->isLoggedIn?$this->session->userData['id']:null;
		$this->data['customer'] = $this->customerModel->setter($customer_id);
		$this->data['page_class']='changedato';
		$this->data['page_name']='changedato';
		$this->data['page_title']=front_translate('General','modify-data');
		$this->data['breadcrumb_title']=front_translate('General','modify-data');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','my-account') => base_url_locale('micuenta')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/changedato', $this->data);
	}
	public function attemptchangedato()
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));

		$rules = [
				'firstname' => 'required|min_length[2]|max_length[100]',
				'lastname' => 'required|min_length[2]|max_length[100]',
				'country' => 'required|min_length[1]|max_length[100]',
				'city' => 'required|min_length[1]|max_length[100]',
				'email' => 'required|max_length[250]|valid_email',
				'address' => 'required|min_length[1]|max_length[250]',
				'cp' => 'required|min_length[1]|max_length[100]',
				'phone' => 'required|max_length[20]|min_length[6]'
	
		];
		if (! $this->validate($rules)) {
			$this->session->setFlashdata('errors','Comprueba los datos ingresados');
			return redirect()->to(base_url_locale('change-dato'));
        }

		$email=$this->request->getPost('email');
		$user = $this->customerModel->where('email',$email)->first();
		// Verifica si el correo electrónico ha sido modificado y si ya existe en otro usuario
		if (!empty($user) && $user['id'] !== $this->session->userData['id']) {
			$this->session->setFlashdata('errors', 'Error, hay un usuario registrado con correo');
			return redirect()->to(base_url_locale('change-dato'));
		}/*
		if (!empty($user)){
			$this->session->setFlashdata('errors','Error, cliente ya existe');
			return redirect()->to(base_url_locale('change-dato'));
		}*/

		$updatedUser = [
			'id' => $this->session->userData['id'],
			'firstname' => $this->request->getPost('firstname'),
			'lastname' => $this->request->getPost('lastname'),
			'email' => $email,
			'country' => $this->request->getPost('country'),
			'city' => $this->request->getPost('city'),
			'cp' => $this->request->getPost('cp'),
			'address' => $this->request->getPost('address'),
			'phone' => $this->request->getPost('phone'),
			'company' => $this->request->getPost('company'),
			'optin' => $this->request->getPost('publicidad') ? 1 : 0 ,
			// Agrega aquí los demás campos que deseas actualizar
		];

		$this->customerModel->save($updatedUser);
		$this->session->setFlashdata('success','Tu datos se ha modificado correctamente');
		return redirect()->to(base_url_locale('change-dato'));

	}

	public function miscarritos()
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));
		$string='';
		$customer_id=$this->session->userData['id'];
		$page=$this->request->getVar('page')?$this->request->getVar('page'):1;
		$alldata=$this->orderModel->getdata($nb_page=30,$page,$customer_id,$order_status=3,$string);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');

		$this->data['page_class']='miscarritos';
		$this->data['page_name']='miscarritos';
		$this->data['page_title']=front_translate('General','saved-carts');
		$this->data['breadcrumb_title']=front_translate('General','saved-carts');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','my-account') => base_url_locale('micuenta')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/miscarritos', $this->data);
	}
	public function detallecarrito($order_id)
	{
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));
		$customer_id=$this->session->userData['id'];
		$this->data['customer'] = $this->customerModel->where('id',$customer_id)->first();
		$this->data['order'] = $this->orderModel->where('id', $order_id)->first();
		if(isset($this->data['customer']['id']) && isset($this->data['order']['id']) && $this->data['order']['customer_id']==$this->data['customer']['id']){
			$this->data['methodpayments']=$this->orderModel->getMethodpayment();
			$this->data['methodshippings']=$this->orderModel->getMethodshipping();
			$this->data['orderitems']=$this->orderModel->getorderitems($order_id);
			if($this->data['order']['address_id']>0){
				$addressModel = new \App\Models\AddressModel();
				$this->data['address'] = $addressModel->where(['id_customer'=>$customer_id,'id'=>$this->data['order']['address_id']])->first();
			}
			$this->data['page_class']='detallecarrito';
			$this->data['page_name']='detallecarrito';
			$this->data['page_title']=front_translate('General','saved-detail');
			$this->data['breadcrumb_title']=front_translate('General','saved-detail');
			$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','my-account') => base_url_locale('micuenta')];
			return view('themes/'. $this->data['currentTheme'] .'/pages/detallecarrito', $this->data);
		}else{
			$this->session->setFlashdata('errors','Ocurrio un error al cargar el pedido');
			return redirect()->to(base_url_locale('message'));
		}
		
	}
	
	public function message()
	{
		/*Email de prueba*/

		/*$message='
		<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Eventos</title>
		</head>
		<body>
		<table cellspacing="0" cellpadding="0" border="0" bgcolor="#fff" align="center" width="100%">
				<tbody>
					<tr>
						<td bgcolor="#FFFFFF" width="100%" valign="top">
							<p>&nbsp;</p>
							<table cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" width="600"
								style="width:600px">
								<tbody>                   
									<tr>
										<td>
											<center>
											<a href="https://easymerx.com/u2/365-tour/london/2024-08-14">
												<img border=0 style="width:600px" src="https://easymerx.com/themes/default/assets/images/email_ticketmaster4.png" alt="">
											</a>
											</center>
										</td>
									</tr>
								
								</tbody>
							</table>
							<p>&nbsp;</p>
						</td>
					</tr>
				</tbody>
			</table>
		</body>
		</html>
		</body>
		</html>
				';
				$email = \Config\Services::email();
				$config['protocol'] = getenv('email_config_protocol');
				$config['SMTPHost'] =  getenv('email_config_SMTPHost');
				$config['SMTPUser'] =  getenv('email_config_SMTPUser');
				$config['SMTPPass'] =  getenv('email_config_SMTPPass');
				$config['SMTPPort'] =  getenv('email_config_SMTPPort');
				$config['SMTPCrypto'] =  getenv('email_config_SMTPCrypto');
				$config['mailType'] = 'html';
				$email->initialize($config);
				$email->setTo('david@dropbusiness.es');
				$email->setFrom('info@dropbusiness.es', 'Ticket Master');
				$email->setSubject('Ticket Master: Confirmación de compra de entradas');
				$email->setMessage($message);
				if ($email->send())
				{
					echo "Email enviado";
				}
		*/

				$message='
				<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Eventos</title>
		</head>
		<body>
		<table cellspacing="0" cellpadding="0" border="0" bgcolor="#fff" align="center" width="100%">
				<tbody>
					<tr>
						<td bgcolor="#FFFFFF" width="100%" valign="top">
							<p>&nbsp;</p>
							<table cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" width="600"
								style="width:600px">
								<tbody>                   
									<tr>
										<td>
											<center>
											<a href="https://easymerx.com/love-of-lesbian/vehn/madrid/2024-06-05">
												<img border=0 style="width:600px" src="https://easymerx.com/themes/default/assets/images/email-See-Tickets4.png" alt="">
											</a>
											</center>
										</td>
									</tr>
								
								</tbody>
							</table>
							<p>&nbsp;</p>
						</td>
					</tr>
				</tbody>
			</table>
		</body>
		</html>
		</body>
		</html>	
		';
		$email = \Config\Services::email();
		$config['protocol'] = getenv('email_config_protocol');
		$config['SMTPHost'] =  getenv('email_config_SMTPHost');
		$config['SMTPUser'] =  getenv('email_config_SMTPUser');
		$config['SMTPPass'] =  getenv('email_config_SMTPPass');
		$config['SMTPPort'] =  getenv('email_config_SMTPPort');
		$config['SMTPCrypto'] =  getenv('email_config_SMTPCrypto');
		$config['mailType'] = 'html';
		$email->initialize($config);
		$email->setTo('david@dropbusiness.es');
		$email->setFrom('info@dropbusiness.es', 'See Tickets');
		$email->setSubject('See Tickets: Confirmación de compra de entradas');
		$email->setMessage($message);
		if ($email->send())
		{
			echo "Email enviado";
		}
		exit;
		/*End email de prueba */
		$this->data['page_class']='message';
		$this->data['page_name']='message';
		$this->data['page_title']='Información';
		$this->data['breadcrumb_title']='Información';
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),"Información" => base_url_locale('message')];
		return view('themes/'. $this->data['currentTheme'] .'/pages/message', $this->data);
	}
	public function checkout()
	{
		$order_id=$this->getsetidcart(false);
		if (!$order_id)
			return redirect()->to(base_url_locale(''));
		
		$customer_id=$this->session->isLoggedIn?$this->session->userData['id']:null;
		$this->data['customer'] = $this->customerModel->setter($customer_id);
		$this->data['order'] = $this->orderModel->where('id', $order_id)->first();
		$this->data['order_details']=$this->orderModel->getorderitems($order_id);
		$this->data['methodshippings']=$this->orderModel->getMethodshipping();
		$this->data['selectcountry']=$this->orderModel->getSelectcountry();

		$this->data['theme_id']='theme_'.$this->orderModel->firsttourid($order_id);
		$this->data['page_class']='checkout_page';
		$this->data['page_name']='checkout';
		$this->data['page_title']=front_translate('General', 'shopping-cart');
		$this->data['breadcrumb_title']=front_translate('General', 'shopping-cart');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General', 'shopping-cart') => ""];
		return view('themes/'. $this->data['currentTheme'] .'/pages/checkout', $this->data);
	}
	public function orderconfirmation($order_id)
	{
		$order_id=decrypt_text($order_id);
		$this->data['order'] = $this->orderModel->where('id', (int)$order_id)->first();
		$this->data['order_details']=$this->orderModel->getorderitems($order_id);
		$this->data['methodshippings']=$this->orderModel->getMethodshipping();
		if (!$this->data['order'])
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		$this->data['page_class']='orderconfirmation';
		$this->data['page_name']='orderconfirmation';
		$this->data['page_title']=front_translate('General','budget-confirmation');
		$this->data['breadcrumb_title']=front_translate('General','budget-confirmation');
		$this->data['breadcrumb'] = [front_translate('General', 'home') => base_url_locale(''),front_translate('General','budget-confirmation') => ""];
		return view('themes/'. $this->data['currentTheme'] .'/pages/orderconfirmation', $this->data);
		
	}
	
	public  function getsetidcart($new=false)
    {
		$session_id = set_cart_id();
		$customer_id=$this->session->isLoggedIn?$this->session->userData['id']:null;
		if ($this->session->isLoggedIn && $customer_id>0){
			$cart=$this->orderModel->select("id")->where(['order_status'=>1,'customer_id'=>$customer_id])->first();
		}else{
			$cart=$this->orderModel->select("id")->where(['order_status'=>1,'session_id'=>$session_id])->first();
		}
		if(!isset($cart['id']) && $new==true){
			/*$def_shipping_id=null;
			$def_shipping_price=0;
			$def_country_iso=null;*/

			$envio=$this->orderModel::getMethodshipping();
			$shipping_id=getenv('SHIPPING_ID_DEFAULT');
			$def_shipping_id=isset($envio[$shipping_id]['id'])?$envio[$shipping_id]['id']:0;
			$def_shipping_price=isset($envio[$shipping_id]['price'])?$envio[$shipping_id]['price']:0;
			$def_country_iso=getenv('SHIPPING_COUNTRY_ISO_DEFAULT');

			$this->db->transStart();
			$this->orderModel->save([
				'session_id'  => $session_id,
				'id_uuid'  => generateUuid(),
				'customer_id'  => $customer_id,
				'order_status' => 1,
				'shipping_id' => $def_shipping_id,
				'order_shipping_price' => $def_shipping_price,
				'order_country' => $def_country_iso,
			]);
			$cart = $this->orderModel->select("id")->find($this->db->insertID());
			$this->db->transComplete();
		}
		return isset($cart['id'])?$cart['id']:false;
    }
	public function itemsaddcart(){
		if (!$this->session->isLoggedIn)
			return redirect()->to(base_url_locale('signin'));
		$cartitem=$this->request->getVar('cartitem');
		if(is_array($cartitem)){
			$order_id=$this->getsetidcart(false);
			$alldata=[];
			foreach ($this->db->query("SELECT id,sku,stock,minimal_quantity FROM tbl_product where status=1 and stock>0")->getResultArray() as $row)
				$alldata[$row['sku']]=$row;
			$error='';
			foreach ($cartitem as $sku => $qty) {
				if(isset($alldata[$sku]) && $qty>0){
					$product=$alldata[$sku];
					$idata['order_id'] = $order_id;
					$idata['product_id'] = $product['id'];
					$idata['product_quantity'] = (int)$qty;
					$idata['qty_replace'] = true;
					$addcart=$this->addcart($idata);
					if($addcart['error']==true)
						$error.=$addcart['msg'].'</br>';
				}
			}
			if($error!=''){
				$this->session->setFlashdata('errors',$error);
			}else{
				$this->session->setFlashdata('success','Productos agregados al carrito correctamente');
			}
			
			return redirect()->to('checkout');
		}else{
			$this->session->setFlashdata('errors','Ocurrio un error al cargar productos al carrito');
			return redirect()->to('checkout');
		}
	}
	public  function addcart($idata)
    {
		$return=['error'=>true,'msg'=>'Error de carrito','data'=>''];
		$product=$this->productModel->getitem($idata['product_id'],'',$this->data['lang_id']);
		$combination=$this->productModel->combinationtxt($idata['combination_id'],$this->data['lang_id']);
        $orderdetails=$this->orderdetailsModel->select("id,combination_id,product_quantity")->where(['order_id'=>$idata['order_id'],'product_id'=>$product['id'],'combination_id'=>$idata['combination_id']])->first();
		if(isset($product['id'])){
			$product['minimal_quantity']=$product['minimal_quantity']>0?$product['minimal_quantity']:1;
			if(isset($idata['qty_replace'])){
				$qty=$idata['product_quantity'];
			}else{
				$qty=isset($orderdetails['id'])?$orderdetails['product_quantity']+$idata['product_quantity']:$idata['product_quantity'];
			}
			$qty=$qty>0?$qty:1;
			/*echo $qty;
			print("idata<pre>");
			print_r($idata);
			print("product<pre>");
			print_r($product);
			print("</pre>");
			print("orderdetails<pre>");
			print_r($orderdetails);
			print("</pre>");
			exit;*/
			if($product['stock']>=$qty){
				if($qty % $product['minimal_quantity']==0 && $qty<999){
						$cdata=[
							'product_id'  => $product['id'],
							'combination_id'  => $idata['combination_id'],
							'product_name'  => $product['name'].($combination!=''?' - '.$combination:''),
							'product_price'  => $product['price'],
							'product_sku'  => $product['sku'],
							'product_quantity'  => $qty,
							'order_id' =>  $idata['order_id'],
							'tax_id'  => 1,
							'tax_val'  => 21,
						];
						if(isset($idata['events_id']))
							$cdata['events_id']=$idata['events_id'];
						if(isset($idata['tour_id']))
							$cdata['tour_id']=$idata['tour_id'];
						if(isset($orderdetails['id']))
							$cdata['id']=$orderdetails['id'];
						$this->db->transStart();
							if($save=$this->orderdetailsModel->save($cdata)){
								$return=['error'=>false,'msg'=>'','data'=>$cdata];
							}
						$this->db->transComplete();
				}else{
						$return=['error'=>true,'msg'=>'<i class="ecicon eci-lg eci-times"></i> El producto <b>'.$product['sku'].'</b> ha de ser en múltiplos de '.$product['minimal_quantity'].' unidades','data'=>''];
				}
			}else{
				$return=['error'=>true,'msg'=>'<i class="ecicon eci-lg eci-times"></i> El producto <b>'.$product['sku'].'</b> ha superado el stock','data'=>''];
			}
		}
		return $return;
    }
	
	public function jsontools(){
		$data=['error'=>true, 'msg'=>'Error, no existe ninguna configuración ','msgclass'=>'danger','tipo'=>'modal'];
		$act=$this->request->getVar('act');
		switch ($act) {
			case 'checkordervalid':
				$order_id=(int)$this->request->getVar('order_id');
				#comprobamos si el pedido existe y si el estado es 3
				$order=$this->orderModel->where(['id'=>$order_id,'order_status'=>3])->first();
				if(isset($order['id'])){
					$order['encryptid']=encrypt_text($order['id']);
					$data=['error'=>false, 'msg'=>'','data'=>$order,'tipo'=>'cart'];
				}else{
					$data=['error'=>true, 'msg'=>'Error, no existe ningun pedido con el id '.$order_id,'data'=>''];
				}
			break;
			case 'addcart':
					$product_id=(int)$this->request->getVar('product_id');
					$product_quantity=(int)$this->request->getVar('quantity');
					$events_id=(int)$this->request->getVar('events_id');
					$tour_id=(int)$this->request->getVar('tour_id');
					$variations=$this->request->getVar('variations');
					$select = array_map(function($item) {
						return $item["attid"] . "|" . $item["valid"];
					}, $variations);
					$combinations =  $this->productModel->icombination($product_id); 
					sort($select);
					$select = serialize($select);
					$combination = array_keys(array_filter($combinations, function($value) use ($select) {
						sort($value);
						return serialize($value) === $select;
					}));
					if(!empty($combination) && isset($combination[0]) && $combination[0]>0){
						$combination_id=$combination[0];
						$order_id=$this->getsetidcart(true);
						if($product_id>0 && $product_quantity>0 && $order_id>0){
								$idata['order_id'] = $order_id;
								$idata['product_id'] = $product_id;
								$idata['product_quantity'] = $product_quantity;
								$idata['qty_replace'] = true;
								$idata['events_id'] = $events_id;
								$idata['tour_id'] = $tour_id;
								$idata['combination_id'] = $combination_id;
								$addcart=$this->addcart($idata);
								if($addcart['error']==false){
									$html='';
									$data=['error'=>false, 'msg'=>$html,'msgclass'=>'success','tipo'=>'alert'];
								}else{
									$data=['error'=>true, 'msg'=>$addcart['msg'],'msgclass'=>'danger','tipo'=>'alert'];
								}
						}
					}else{
						$data=['error'=>true, 'msg'=>'Error, no hemos encontrado la combinación seleccionada','msgclass'=>'danger','tipo'=>'alert'];
					}
				break;
			case 'removecart':
				$order_id=$this->getsetidcart(false);
				if ($order_id>0){
					$product_id=(int)$this->request->getVar('product_id');
					$combination_id=(int)$this->request->getVar('combination_id');
					$product = $this->productModel->where(['id'=>$product_id])->first();
					if($product && $order_id)
						$rdata=$this->orderModel->removecart($order_id,$product['id'],$combination_id);
					$data=['error'=>false, 'msg'=>'','data'=>$rdata,'tipo'=>'cart'];
				}else{
					$data=['error'=>true, 'msg'=>'','data'=>'','tipo'=>'cart'];
				}
			break;
			case 'getcart':
					$order_id=$this->getsetidcart(false);
					if($order_id>0){
						$rdata=$this->orderModel->getcart($order_id);
						$data=['error'=>false, 'msg'=>'','data'=>$rdata,'tipo'=>'cart'];
					}else{
						$data=['error'=>true, 'msg'=>'','data'=>'','tipo'=>'cart'];
					}
				break;
			case 'loadcheckout':
				$order_id=$this->getsetidcart(false);
				if($order_id>0){
					$rdata=$this->orderModel->getcart($order_id,true);
					$rdata['methodshippings']=$this->orderModel->getMethodshippingFilter([
																				'country'=>$rdata['cart']['order_country'],
																				'shipping_id'=>$rdata['cart']['shipping_id']
																				]);
					$data=['error'=>false, 'msg'=>'','data'=>$rdata,'tipo'=>'checkout','getMethodshippingFilter'=>[
						'country'=>$rdata['cart']['order_country'],
						'shipping_id'=>$rdata['cart']['shipping_id']
						]];
				}else{
					$data=['error'=>true, 'msg'=>'','data'=>'','tipo'=>'cart'];
				}
			break;
			case 'updatecart':
				$product_id=(int)$this->request->getVar('product_id');
				$product_quantity=(int)$this->request->getVar('product_quantity');
				$combination_id=(int)$this->request->getVar('combination_id');
				$order_id=$this->getsetidcart(false);
				$idata['order_id'] = $order_id;
				$idata['product_id'] = $product_id;
				$idata['combination_id'] = $combination_id;
				$idata['product_quantity'] = $product_quantity;
				$idata['qty_replace'] = true;
				$addcart=$this->addcart($idata);
				if($addcart['error']==false){
					$data=['error'=>false, 'msg'=>'','data'=>'','tipo'=>'cart'];
				}else{
					$data=['error'=>true, 'msg'=>$addcart['msg'],'msgclass'=>'danger','tipo'=>'cart'];
				}
			break;
			case 'cartshippingupdate':
					$shipping_id=(int)$this->request->getVar('shipping_id');
					$order_id=(int)$this->request->getVar('order_id');
					$methodshippings=$this->orderModel->getMethodshipping();
					if(isset($methodshippings[$shipping_id]))
						$rdata=$this->orderModel->where(['id'=>$order_id])->set(['shipping_id'=>$shipping_id,'order_shipping_price'=>$methodshippings[$shipping_id]['price']])->update();
					
					$data=['error'=>false, 'msg'=>'','data'=>'','tipo'=>'cart'];
				
			break;
			case 'cartcountryupdate':
				$country=(string)$this->request->getVar('country');
				$order_id=(int)$this->request->getVar('order_id');
				$methodshippings=$this->orderModel->getMethodshipping();
				$countries=$this->orderModel->getSelectcountryFilter([
					'country'=>$country
				]);
				if(!empty($countries) && isset($countries['shipping'][0]) && isset($methodshippings[$countries['shipping'][0]])){
					$shipping_id=$countries['shipping'][0];
					$shipping=$methodshippings[$shipping_id];
					$rdata=$this->orderModel->where(['id'=>$order_id])->set(['order_country'=>$country,'shipping_id'=>$shipping_id,'order_shipping_price'=>$shipping['price']])->update();
				}
				
				$data=['error'=>false, 'msg'=>'','data'=>'','tipo'=>'cart','countries'=>$countries];
			
			break;
			case 'cartpaymentupdate':
					$payment_id=(int)$this->request->getVar('payment_id');
					$order_id=(int)$this->request->getVar('order_id');
					$rdata=$this->orderModel->where(['id'=>$order_id])->set(['payment_id'=>$payment_id])->update();
					$data=['error'=>false, 'msg'=>'','data'=>'','tipo'=>'cart'];
			break;
			case 'updateordercustomer':
				$order_id=(int)$this->request->getVar('order_id');
				$order = $this->orderModel->Where(['id'=>$order_id,'order_status'=>1])->first();
				$datai = [
					'country' => $this->request->getVar('country'),
					'city' => $this->request->getVar('city'),
					'cp' => $this->request->getVar('cp'),
					'phone' => $this->request->getVar('phone'),
					'address' => $this->request->getVar('address'),
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'status' => 1,
				];
				#preguntar si existe el customer_id en order para luego actualizarlo solo los datos del cliente
				if(isset($order['customer_id']) && $order['customer_id']>0)
					$datai['id']=$order['customer_id'];

				$this->customerModel->save($datai);
				#preguntar si existe el customer_id en order para luego actualizarlo si es nuevo cliente la tabla order
				if(!isset($order['customer_id']) || $order['customer_id']==0){
					$this->orderModel->where(['id'=>$order_id])->set([
						'customer_id'=>$this->db->insertID(),
						'order_email'=>$datai['email'],
						])->update();
				}
				#actualizar shipping_id en order
				$shipping_id=(int)$this->request->getVar('shipping_id');
				$this->orderModel->where(['id'=>$order_id])->set(['shipping_id'=>$shipping_id])->update();

				$data=['error'=>false, 'msg'=>'','data'=>$datai,'tipo'=>'confirmarpedido'];
			break;
			case 'confirmorder':
				$error= false;
				$error_msg ="";
				$customer_id=(int)$this->request->getVar('customer_id');
				$datai = [
					'id' => $customer_id,
					'country' => $this->request->getVar('country'),
					'city' => $this->request->getVar('city'),
					'cp' => $this->request->getVar('cp'),
					'phone' => $this->request->getVar('phone'),
					'address' => $this->request->getVar('address'),
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'passwd' => md5($this->request->getVar('passwd')),
					'password_token' => md5(uniqid()),
					'status' => 1,
					'condiciones' => $this->request->getVar('condiciones'),
				];

				if(trim($datai['email'])== "" 
				|| trim($datai['city'])== "" 
				|| trim($datai['country'])== "" 
				|| trim($datai['lastname'])== ""  
				|| trim($datai['firstname'])== "" 
				|| $datai['condiciones']!= 1 
				|| trim($datai['cp'])== ""  
				|| trim($datai['phone'])== ""  
				|| trim($datai['address'])== ""  )
				{
					$error=true;
					$error_msg ="*Los campos requeridos deben ser completados";
				}
				if($error==false){
					$this->db->transStart();
						$this->customerModel->save($datai);
						$customer = $this->customerModel->find($this->db->insertID());
					$this->db->transComplete();
					$customer_id=$customer["id"];
					$order_id=(int)$this->request->getVar('order_id');
					$order_obs=(string)$this->request->getVar('order_obs');
					$order = $this->orderModel->Where(['id'=>$order_id,'order_status'=>1])->first();
					$nproducts = $this->orderdetailsModel->where(['order_id'=>$order['id']])->countAllResults();
					if(isset($order['id']) && $nproducts>0){
						$this->orderModel->where(['id'=>$order_id])->set([
							'customer_id'=>$customer_id,
							'order_firstname'=>$customer['firstname'],
							'order_lastname'=>$customer['lastname'],
							'order_company'=>$customer['company'],
							'order_email'=>$customer['email'],
							'order_address'=>$customer['address'],
							'order_obs'=>$order_obs,
							])->update();
						if($this->orderModel->orderprocess($order_id)){ 
							$data=['error'=>false, 'msg'=>'','data'=>encrypt_text($order_id),'tipo'=>'confirmarpedido'];
	 					}else{ 
							$data=['error'=>true, 'msg'=>'Ocurrió un error al enviar el pedido, por favor intente de nuevo o póngase en contacto con el administrador del sistema','data'=>'','tipo'=>'cart'];			} 
					}else{
						$data=['error'=>true, 'msg'=>'Ocurrio un error al cargar la comprobación del pedido','data'=>'','tipo'=>'cart'];
					}
				}else{
					$data=['error'=>true, 'msg'=>$error_msg,'data'=>'','tipo'=>'cart'];
				}
			break;
			case 'get_guiatalla':
				$page_id=(int)$this->request->getVar('page_id');
				$talla =$this->productModel->get_guiaTalla($page_id, $this->data['lang_id']);
				if(!empty($talla)){
					$data=['error'=>false, 'msg'=>'','data'=>$talla,'tipo'=>'talla'];
				}else{
					$data=['error'=>true, 'msg'=>'Error, no hemos encontrado la talla seleccionada','data'=>'','tipo'=>'talla'];
				}
			break;
			default:
				# code...
				break;
		}
		return $this->response->setJSON($data);
	}
	public function changelang($lang='en'){
		$lang_id=isset($this->data['languages']['codes'][$lang]['id'])?$this->data['languages']['codes'][$lang]['id']:false;
		if($lang_id==false)
			return redirect()->to('/');

		$request = service('request');
        $urlOrigen = $request->getServer('HTTP_REFERER');
		$defaultLocale = 'es';
		$lang=($defaultLocale!=$lang? $lang . '/' : '');
		$url=$lang;
		
        if ($urlOrigen && (strpos($urlOrigen, 'latostadora') !== false || strpos($urlOrigen, 'localhost') !== false || strpos($urlOrigen, 'breathegreen') !== false)) {
			$segment = explode('/', $urlOrigen);
			$total_segment=count($segment);
			$endsegment = end($segment);
			if(in_array($endsegment,['contacto','recuperar-contrasena','signup','checkout','signin','micuenta','misdatos','change-password','change-dato','miscarritos'])){
				$url=$lang.$endsegment;
			}elseif($total_segment>3 && in_array($segment[$total_segment-2],['producto'])){
				$item=$this->productModel->getLinkrewrites($endsegment);
				$url=$lang.(isset($item[$lang_id])?'producto/'.$item[$lang_id]:'');
			}elseif($total_segment>3 && in_array($segment[$total_segment-2],['contenido'])){
				$item=$this->pageModel->getLinkrewrites($endsegment);
				$url=$lang.(isset($item[$lang_id])?'contenido/'.$item[$lang_id]:'');
			}
        }
		return redirect()->to(base_url_locale($url));
	}
	
}
