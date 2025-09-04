<?php namespace App\Controllers\Front;
use App\Controllers\FrontController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\BrandModel;
use App\Models\ProductImageModel;
use Elasticsearch\ClientBuilder;
use App\Models\CustomerModel;
use App\Models\AddressModel;
class Cron extends FrontController
{
	protected $productModel;
    protected $categoryModel;
    protected $brandModel;
    protected $productImageModel;
	protected $customerModel;
	protected $addressModel;
    public function __construct()
    {
		$this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->brandModel = new BrandModel();
        $this->productImageModel = new ProductImageModel();
		$this->customerModel = new CustomerModel();
		$this->addressModel = new AddressModel();
    }
	#send_email_tours
	public function sendemailtours()
	{
        @ini_set('memory_limit', '-1');
		@ini_set('max_execution_time', 0);
		if (!($token = $this->request->getVar('secure')) || $token != 'G4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ')
   			 die('Access denied');
        #solo se envian los pedidos de ayer
        $params = [
            'created_order_at_start' => date('Y-m-d 00:00:00', strtotime('-1 day')),
            'created_order_at_end' => date('Y-m-d 23:59:59', strtotime('-1 day')),
        ];
        /*$params = [
            'created_order_at_start' => date('2025-06-20 00:00:00'),
            'created_order_at_end' => date('2025-06-30 23:59:59'),
        ];*/
		$response=sendorder_tours_custom($params);
        $this->response->setJSON($response);
        $this->response->setStatusCode(200);
        $this->response->send();
        exit;
	}
   
}
