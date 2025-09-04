<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\EventsModel;
use App\Models\ArtistsModel;
use App\Models\ToursModel;
use App\Models\ProductmodelsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class Order extends BaseController
{
    protected $orderModel;
    protected $artistsModel;
    protected $toursModel;
    protected $eventsModel;
    protected $productmodelsModel;
    protected $productModel;
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->artistsModel = new ArtistsModel();
        $this->toursModel = new ToursModel();
        $this->eventsModel = new EventsModel();
        $this->productmodelsModel = new ProductmodelsModel();
        $this->productModel = new ProductModel();
        $this->data['currentAdminMenu'] = 'ecommerce';
        $this->data['currentAdminSubMenu'] = 'order';
        $this->data['statuses'] = $this->orderModel::getStatuses();
        $this->data['shippings'] = $this->orderModel::getMethodshipping();
    }

    public function add()
    {
        return view('admin/order/form', $this->data);
    }

    public function index()
    {
        $this->data['artists']=$this->artistsModel->list();
        $this->data['tours']=$this->toursModel->list();
        $this->data['events']=$this->eventsModel->list();

        $this->data['s_idate']=$this->request->getVar('s_idate');
        $this->data['s_fdate']=$this->request->getVar('s_fdate');
        $this->data['s_status']=$this->request->getVar('s_status');
        $this->data['s_artist']=$this->request->getVar('s_artist');
        $this->data['s_tour']=$this->request->getVar('s_tour');
        $this->data['s_event']=$this->request->getVar('s_event');
        $this->data['s_name']=trim($this->request->getVar('s_name'));
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->orderModel->getdata($nb_page=50,$page,'',$this->data['s_status'],$this->data['s_name'],$this->data['s_idate'],$this->data['s_fdate'],$this->data['s_artist'],$this->data['s_tour'],$this->data['s_event']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/order/index', $this->data);
    }

    public function show($order_id)
    {
        $this->data['methodpayments']=$this->orderModel->getMethodpayment();
		$this->data['methodshippings']=$this->orderModel->getMethodshipping();
        $this->data['orderitems']=$this->orderModel->getorderitems($order_id);
        $this->data['order'] = $this->orderModel->where('id', $order_id)->first();
        
        // Obtener el customer_id de la orden actual
        $customer_id = $this->data['order']['customer_id'];

        // Obtener los datos del cliente asociado a la orden
        $customerModel = new CustomerModel();
        $this->data['customer'] = $customerModel->find($customer_id);


        return view('admin/order/show', $this->data);
    }
    public function export()
    {
        ini_set ( 'max_execution_time', -1);
        $methodpayments=$this->orderModel->getMethodpayment();
		$methodshippings=$this->orderModel->getMethodshipping();
        $statuses = $this->orderModel::getStatuses();
        //actualizar models_code
        $this->productModel->updateModelsCode();
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle('carrito');
        $activeSheet->setCellValue('A1', 'Lista de carritos');
        $activeSheet->setCellValue('A2', 'Creado :'.date('d/m/Y') );
        $activeSheet->setCellValue('A3', 'Id pedido');
        $activeSheet->setCellValue('B3', 'Fecha pedido');
        $activeSheet->setCellValue('C3', 'Estado pedido');
        $activeSheet->setCellValue('D3', 'Referencia adyen');
        $activeSheet->setCellValue('E3', 'Metodo pago');
        $activeSheet->setCellValue('F3', 'Metodo envío');
        $activeSheet->setCellValue('G3', 'Total envío');
        $activeSheet->setCellValue('H3', 'Total pedido');
        $activeSheet->setCellValue('I3', 'Nombre cliente');
        $activeSheet->setCellValue('J3', 'Email cliente');
        $activeSheet->setCellValue('K3', 'Telefono cliente');
        $activeSheet->setCellValue('L3', 'Direccion cliente');
        $activeSheet->setCellValue('M3', 'Ciudad cliente');
        $activeSheet->setCellValue('N3', 'CP cliente');
        $activeSheet->setCellValue('O3', 'País cliente');
        $activeSheet->setCellValue('P3', 'Nombre producto');
        $activeSheet->setCellValue('Q3', 'SKU producto');
        $activeSheet->setCellValue('R3', 'Ref. Tostadora');
        #agregar columan color,talla,nombre modelo,codigo modelo
        $activeSheet->setCellValue('S3', 'Color');
        $activeSheet->setCellValue('T3', 'Talla');
        $activeSheet->setCellValue('U3', 'Nombre modelo');
        $activeSheet->setCellValue('V3', 'Codigo modelo');
        $activeSheet->setCellValue('W3', 'Precio/ud producto');
        $activeSheet->setCellValue('X3', 'Cantidad');
        $activeSheet->setCellValue('Y3', 'Total producto');
        $i=3;

        $s_artist=$this->request->getVar('s_artist');
        $s_tour=$this->request->getVar('s_tour');
        $s_event=$this->request->getVar('s_event');
        $s_name=$this->request->getVar('s_name');
        $s_idate=$this->request->getVar('s_idate');
        $s_fdate=$this->request->getVar('s_fdate');
        $s_status=$this->request->getVar('s_status');
        // Obtener los datos
        $conditions_and = [];
        $conditions_or = [];
        $sql_conditions = '';
        if($s_tour) $conditions_and[] = "tbl_order_details.tour_id = $s_tour";
        if($s_event) $conditions_and[] = "tbl_order_details.events_id = $s_event";
        if($s_artist) $conditions_and[] = "tbl_events.artists_id = $s_artist";
        #FILTRO FECHAS
        if($s_idate) $conditions_and[] = "tbl_order.created_order >= '$s_idate 00:00:00'";
        if($s_fdate) $conditions_and[] = "tbl_order.created_order <= '$s_fdate 23:59:59'";
        #FILTRO TEXTO
        if($s_name){
         $conditions_or[] = "CONCAT(tbl_customer.firstname, ' ', tbl_customer.lastname) LIKE '%$s_name%'";
         $conditions_or[] = "tbl_order.id_uuid LIKE '%$s_name%'";
         $conditions_or[] = "tbl_order.order_reference LIKE '%$s_name%'";
         $conditions_or[] = "tbl_customer.firstname LIKE '%$s_name%'";
         $conditions_or[] = "tbl_customer.lastname LIKE '%$s_name%'";
         $conditions_or[] = "tbl_customer.email LIKE '%$s_name%'";
         $conditions_or[] = "tbl_customer.phone LIKE '%$s_name%'";
         $conditions_or[] = "tbl_order.payment_reference LIKE '%$s_name%'";
         $sql_conditions .= " AND (" . implode(' OR ', $conditions_or) . ")";
        }
        if($conditions_and) 
            $sql_conditions .= " AND " . implode(' AND ', $conditions_and);
        
    $orders = $this->db->query("
    SELECT 
        tbl_order_details.product_sku,
        tbl_order_details.product_name,
        tbl_order_details.product_price,
        tbl_order_details.product_quantity,
        tbl_order_details.product_id,
        tbl_order_details.combination_id,
        tbl_combinations.reference as combinatio_ref,
        tbl_combinations.models_code as models_code,
        tbl_order.id,
        tbl_order.order_reference,
        tbl_order.payment_reference, 
        tbl_order.order_email,
        tbl_order.updated_at,
        tbl_order.created_order,
        tbl_order.order_status,
        tbl_order.order_total,
        tbl_order.order_shipping_price,
        tbl_order.payment_id,
        tbl_order.payment_data,
        tbl_order.payment_reference,
        tbl_order.shipping_id,
        tbl_customer.firstname, 
        tbl_customer.lastname, 
        tbl_customer.address, 
        tbl_customer.phone,
        tbl_customer.city,
        tbl_customer.country,
        tbl_customer.cp
    FROM tbl_order_details
    INNER JOIN tbl_order ON tbl_order.id = tbl_order_details.order_id
    INNER JOIN tbl_customer ON tbl_order.customer_id = tbl_customer.id
    INNER JOIN tbl_combinations ON tbl_order_details.combination_id = tbl_combinations.id
    LEFT JOIN tbl_events ON tbl_order_details.events_id = tbl_events.id
    WHERE tbl_order.order_status = 3
    $sql_conditions
    ORDER BY tbl_order.order_reference DESC
")->getResultArray();

#listamos todo los tbl_product_models como key ponemos el code como key
$product_models = $this->productmodelsModel->getlist();
#get all combinations ids unique numerico
$combinations_ids = array_unique(array_column($orders, 'combination_id'));
$datacombinations=$this->productModel->get_combinations($combinations_ids);
foreach ($orders as $order) {
    // Obtener los detalles del pedido
   /* $orderDetails = $this->db->table('tbl_order_details')
        ->where('order_id', $order['id'])
        ->get()
        ->getResultArray();*/

    // Agregar los datos del pedido principal
    $i++;
    $shipping_abr=$methodshippings[$order['shipping_id']]['abr']??'';
    $model_name=$product_models[$order['models_code']]['name']??'';
    $model_code=$product_models[$order['models_code']]['code']??'';
    $talla=$datacombinations[$order['combination_id']][2][0]['name']??'';
    $color=$datacombinations[$order['combination_id']][3][0]['name']??'';
    $activeSheet
        ->setCellValue('A' . $i, $order['order_reference'])
        ->setCellValue('B' . $i, $order['created_order'])
        ->setCellValue('C' . $i, (isset($statuses[$order['order_status']]) ? $statuses[$order['order_status']] : ''))
        ->setCellValue('D' . $i, get_value_from_data($order['payment_data'],'merchantReference'))
        ->setCellValue('E' . $i, get_value_from_data($order['payment_data'],'paymentMethod'))
        ->setCellValue('F' . $i, $shipping_abr)
        ->setCellValue('G' . $i, number_format($order['order_shipping_price'], 2, '.', ''))
        ->setCellValue('H' . $i, number_format($order['order_total'], 2, '.', ''))
        ->setCellValue('I' . $i, $order['firstname'] . ' ' . $order['lastname'])
        ->setCellValue('J' . $i, $order['order_email'])
        ->setCellValue('K' . $i, $order['phone'])
        ->setCellValue('L' . $i, $order['address'])
        ->setCellValue('M' . $i, $order['city'])
        ->setCellValue('N' . $i, $order['cp'])
        ->setCellValue('O' . $i, getCountryNameByISO($order['country'])) // Ajustar con los valores reales.
        ->setCellValue('P' . $i, $order['product_name']) // Nombre del producto
        ->setCellValue('Q' . $i, $order['product_sku']) // SKU del producto
        ->setCellValue('R' . $i, $order['combinatio_ref']) // SKU del producto
        ->setCellValue('S' . $i, $color) // Color
        ->setCellValue('T' . $i, $talla) // Talla
        ->setCellValue('U' . $i, $model_name) // Nombre modelo
        ->setCellValue('V' . $i, $model_code) // Codigo modelo
        ->setCellValue('W' . $i, number_format($order['product_price'], 2, '.', '')) // Precio por unidad
        ->setCellValue('X' . $i, $order['product_quantity']) // Cantidad
        ->setCellValue('Y' . $i, number_format($order['product_price'] * $order['product_quantity'], 2, '.', '')); // Total del producto
}
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="easymerx_pedidos_'.date('d-m-Y').'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
