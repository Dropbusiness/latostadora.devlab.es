<?php namespace App\Models;
use CodeIgniter\Model;
class OrderModel extends Model
{
    protected $table      = 'tbl_order';
	protected $primaryKey = 'id';
	protected $allowedFields = [
        'id',
        'id_uuid',
        'session_id',
        'customer_id',
        'address_id',
        'shipping_id',
        'payment_id',
        'order_total',
        'order_nprod',
        'order_subtotal',
        'order_shipping_price',
        'order_iva',
        'order_recargo',
        'order_prontopago',
        'order_firstname',
        'order_lastname',
        'order_company',
        'order_cip',
        'order_usercode',
        'order_email',
        'order_total',
        'order_status',
        'order_reference',
        'order_obs',
        'order_erp_id',
        'order_address',
        'sendemail',
        'payment_reference',
        'payment_data',
        'payment_datab',
        'payment_session',
        'order_country',
        'created_order',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
    const STATUSES = [
       # '0' => 'Carrito Eliminado',
	  # '1' => 'Carrito abierto',
		 #'2' => 'Carrito guardado',
        '3' => 'Pedido Confirmado',
        '4' => 'Pedido Cancelado',
        '5' => 'Error pedido',
	];

    const METHODPAYMENT = [
		'1' => ['id'=>'1','name'=>'Condiciones acordadas','erp_code'=>''],
		'2' => ['id'=>'2','name'=>'Transferencia bancaria','erp_code'=>''],
        '3' => ['id'=>'3','name'=>'Pago con tarjeta','erp_code'=>''],
        ];

    const METHODSHIPPING = [
        '1' => [
            'id' => '1',
            'name' => 'Envío Standard, entrega en 2 a 5 días laborales',
            'price' => 2.95,
            'erp_code' => '1',
            'abr' => 'Envío Standard',
            'selected' => 0
        ],
        '2' => [
            'id' => '2',
            'name' => 'Envío Express, entrega en 48 horas',
            'price' => 4.90,
            'erp_code' => '2',
            'abr' => 'Envío Express',
            'selected' => 0
        ],
        '3' => [
            'id' => '3', // Corregido: era '1', ahora '3' para consistencia
            'name' => 'Envío Standard Internacional',
            'price' => 8.45,
            'erp_code' => '3',
            'abr' => 'Envío Standard',
            'selected' => 0
        ],
    ];

    const SELECTCOUNTRY = [
        // España - Servicio completo
        '1' => [
            'id' => '1',
            'name' => 'España',
            'iso' => 'ES',
            'shipping' => ['1', '2']
        ],
        // Países europeos - Envío Standard únicamente
        '2' => [
            'id' => '2',
            'name' => 'France',
            'iso' => 'FR',
            'shipping' => ['3']
        ],
        '3' => [
            'id' => '3',
            'name' => 'Germany',
            'iso' => 'DE',
            'shipping' => ['3']
        ],
        '4' => [
            'id' => '4',
            'name' => 'Italy',
            'iso' => 'IT',
            'shipping' => ['3']
        ],
        '5' => [
            'id' => '5',
            'name' => 'Andorra',
            'iso' => 'AD',
            'shipping' => ['3']
        ],
        '6' => [
            'id' => '6',
            'name' => 'Austria',
            'iso' => 'AT',
            'shipping' => ['3']
        ],
        '7' => [
            'id' => '7',
            'name' => 'Bulgaria',
            'iso' => 'BG',
            'shipping' => ['3']
        ],
        '8' => [
            'id' => '8',
            'name' => 'Cyprus',
            'iso' => 'CY',
            'shipping' => ['3']
        ],
        '9' => [
            'id' => '9',
            'name' => 'Czech Republic',
            'iso' => 'CZ',
            'shipping' => ['3']
        ],
        '10' => [
            'id' => '10',
            'name' => 'Denmark',
            'iso' => 'DK',
            'shipping' => ['3']
        ],
        '11' => [
            'id' => '11',
            'name' => 'Finland',
            'iso' => 'FI',
            'shipping' => ['3']
        ],
        '12' => [
            'id' => '12',
            'name' => 'Greece',
            'iso' => 'GR',
            'shipping' => ['3']
        ],
        '13' => [
            'id' => '13',
            'name' => 'Hungary',
            'iso' => 'HU',
            'shipping' => ['3']
        ],
        '14' => [
            'id' => '14',
            'name' => 'Ireland',
            'iso' => 'IE',
            'shipping' => ['3']
        ],
        '15' => [
            'id' => '15',
            'name' => 'Latvia',
            'iso' => 'LV',
            'shipping' => ['3']
        ],
        '16' => [
            'id' => '16',
            'name' => 'Lithuania',
            'iso' => 'LT',
            'shipping' => ['3']
        ],
        '17' => [
            'id' => '17',
            'name' => 'Luxembourg',
            'iso' => 'LU',
            'shipping' => ['3']
        ],
        '18' => [
            'id' => '18',
            'name' => 'Malta',
            'iso' => 'MT',
            'shipping' => ['3']
        ],
        '19' => [
            'id' => '19',
            'name' => 'Netherlands',
            'iso' => 'NL',
            'shipping' => ['3']
        ],
        '20' => [
            'id' => '20',
            'name' => 'Norway',
            'iso' => 'NO',
            'shipping' => ['3']
        ],
        '21' => [
            'id' => '21',
            'name' => 'Poland',
            'iso' => 'PL',
            'shipping' => ['3']
        ],
        '22' => [
            'id' => '22',
            'name' => 'Romania',
            'iso' => 'RO',
            'shipping' => ['3']
        ],
        '23' => [
            'id' => '23',
            'name' => 'Slovakia',
            'iso' => 'SK',
            'shipping' => ['3']
        ],
        '24' => [
            'id' => '24',
            'name' => 'Slovenia',
            'iso' => 'SI',
            'shipping' => ['3']
        ],
        '25' => [
            'id' => '25',
            'name' => 'Sweden',
            'iso' => 'SE',
            'shipping' => ['3']
        ],
        '26' => [
            'id' => '26',
            'name' => 'Switzerland',
            'iso' => 'CH',
            'shipping' => ['3']
        ],
        '27' => [
            'id' => '27',
            'name' => 'United Kingdom',
            'iso' => 'GB',
            'shipping' => ['3']
        ],
    ];
    /**
     * Filtra los metodos de envio segun los parametros
     * @param array $params ejemplo: ['country'=>'ES','shipping_id'=>'1']
     * @return array
     */
    public static function getMethodshippingFilter($params)
	{
        if(!isset($params['country']) || $params['country']=='')
            return [];#si no hay country o shipping_id en el pedido, devolvemos un array vacio

        $country=isset($params['country'])?$params['country']:'ES'; #default country
        $shipping_id=isset($params['shipping_id'])?$params['shipping_id']:1; #default shipping id
        $country_item=self::getSelectcountryFilter(['country'=>$country]);
        $shipping_country=isset($country_item['shipping'])?$country_item['shipping']:[];
        $methodshipping=array_filter(self::METHODSHIPPING,function($item) use ($shipping_country,$shipping_id){
            return in_array($item['id'],$shipping_country);
        });
        #cambiamos el selected=1 al id de shipping_id
        foreach($methodshipping as $key=>$item)
            if($item['id']==$shipping_id)
                $methodshipping[$key]['selected']=1;

        return $methodshipping;
    }
   /**
     * Obtiene un país de SELECTCOUNTRY por ID o código ISO
     * 
     * @param array $params ['country' => 'ES'] o ['country' => '1']
     * @return array Datos del país encontrado o España por defecto
     */
    public static function getSelectcountryFilter(array $params): array
    {
        $country=isset($params['country'])?$params['country']:''; #default country
        // Buscar por ID directo (clave del array)
        if (isset(self::SELECTCOUNTRY[$country])) {
            return self::SELECTCOUNTRY[$country];
        }
        // Buscar por código ISO
        foreach (self::SELECTCOUNTRY as $item) {
            if (strtoupper($item['iso']) === strtoupper($country)) {
                return $item;
            }
        }
        return [];
    }
   
    public  function getdata($nb_page, $page,$customer_id='',$order_status='',$string='',$idate='',$fdate='',$artist='',$tour='',$event='')
    {
        $this->table('tbl_order')
        ->select("tbl_order.*, tbl_customer.firstname, tbl_customer.lastname, tbl_customer.city, tbl_customer.phone, tbl_customer.address, tbl_customer.country, tbl_customer.cp") // Selecciona columnas adicionales
        ->join('tbl_order_details', 'tbl_order.id = tbl_order_details.order_id', 'left')
        ->join('tbl_customer', 'tbl_order.customer_id = tbl_customer.id', 'left') // Realiza el JOIN
        ->groupBy('tbl_order.id')
        ->orderBy('order_reference','DESC');
        if($customer_id!='')
			$this->where('tbl_order.customer_id ', $customer_id);
        if($tour!='')
            $this->where('tbl_order_details.tour_id ', $tour);
        if($event!='')
            $this->where('tbl_order_details.events_id ', $event);
        if($idate!='')
            $this->where('tbl_order.created_order >=', $idate.' 00:00:00');
        if($fdate!='')
            $this->where('tbl_order.created_order <=', $fdate.' 23:59:59');
        #si es artista hacemos join con tbl_event columna artists_id  tbl_order_details.events_id=tbl_events.id
        if($artist!=''){
            $this->join('tbl_events', 'tbl_order_details.events_id = tbl_events.id', 'left');
            $this->where('tbl_events.artists_id ', $artist);
        }
      //  if($order_status!='')
		//	$this->where('tbl_order.order_status ', $order_status);
        if($order_status!='' || is_array($order_status)){
            $sql=is_array($order_status)?"tbl_order.order_status in ('".implode("','",$order_status)."')":"tbl_order.order_status = '".(int)$order_status."'";
            $this->where($sql);
        }else{
            $this->where('tbl_order.order_status >=',2);
        }
        if($string!='')
			$this->groupStart()
				->orLike(['tbl_order.id_uuid' => $string, 
                    'tbl_order.order_reference' => $string, 
                    'tbl_customer.firstname' => $string, 
                    'tbl_customer.lastname' => $string, 
                    'tbl_customer.email' => $string, 
                    'tbl_customer.phone' => $string,
                    'CONCAT(tbl_customer.firstname, " ", tbl_customer.lastname)' => $string,
                    'tbl_order.payment_reference' => $string,
                    ]
                    )
				->groupEnd();
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public  function getAllTotals()
    {
        $data=[];
        $data['orders']=$this->db->table('tbl_order')->countAllResults();
        $data['products']=$this->db->table('tbl_product')->countAllResults();
        $data['customers']=$this->db->table('tbl_customer')->countAllResults();
        $data['contacts']=$this->db->table('tbl_contact')->countAllResults();
        return $data;
    }
    public  function getAllCart($date_from, $date_to, $granularity = '')
    {
        if ($granularity == 'day') {
            $data = array();
            $result = $this->query(
                '
                SELECT LEFT(d.`updated_at`, 10) AS date, COUNT(DISTINCT(d.`id`)) as total
                FROM `tbl_order` d
                WHERE  d.`updated_at` BETWEEN "' . ($date_from) . ' 00:00:00" AND "' . ($date_to) . ' 23:59:59"
                GROUP BY LEFT(d.`updated_at`, 10)'
            )->getResult('array');
            foreach ($result as $row) {
                $data[strtotime($row['date'])] = ['total'=>$row['total']];
            }
            return $data;
        }
    }
    public  function removecart($order_id,$product_id,$combination_id)
    {
        return $this->query('DELETE FROM `tbl_order_details` WHERE order_id='.(int)$order_id.' and combination_id='.(int)$combination_id.' and product_id='.(int)$product_id);
    }
    public  function firsttourid($order_id)
    {
        $iorder = $this->query("SELECT events_id, tour_id FROM `tbl_order_details` 
        WHERE order_id=".(int)$order_id)->getRowArray();
        return isset($iorder['tour_id'])?$iorder['tour_id']:'';
    }
    
    public  function getcart($order_id='',$savetotals=false)
    {
        $result=$cart=[];
        $order = $this->query("SELECT * FROM `tbl_order` WHERE id=".(int)$order_id)->getRowArray();
        $orderlist = $this->query('
        SELECT od.product_id,
        od.product_quantity as quantity,
        od.product_sku as sku,
        od.tax_id,
        od.tax_val,
        od.product_price as price,
        od.events_id,
        od.combination_id,
        od.product_name as name,
        pl.link_rewrite,
        p.img,
        p.stock,
        p.minimal_quantity as mqty,
        od.order_id,
        (od.product_price*od.product_quantity) as subtotal
        FROM `tbl_order_details` as od
        INNER JOIN tbl_product as  p ON od.product_id=p.id
        INNER JOIN tbl_product_lang as pl ON  pl.product_id=p.id 
        WHERE od.order_id = '.$order_id.' and id_lang=1
        ORDER BY od.id ASC'
        )->getResult('array');
        $totalorder = $this->query('SELECT ROUND(SUM(product_quantity * product_price),2) as subtotal, ROUND(SUM(product_quantity * product_price),2) as total, ROUND(SUM(product_quantity * product_price),2) as totaliva, SUM(product_quantity) as nprod FROM `tbl_order_details` WHERE order_id ='.$order_id)->getRowArray();
       
        $result['cart']=$order;
        $result['cart_list']=$orderlist;
        $result['cart_items']=count($orderlist);
        $result['cart_totaliva']=$totalorder['totaliva'];
        $result['cart_total']=$totalorder['total']+$order['order_shipping_price'];
        $result['cart_subtotal']=$totalorder['subtotal'];
        $result['cart_nprod']=$totalorder['nprod'];
        if($savetotals){
            $this->table('tbl_order')
            ->where('id',$order_id)
            ->set([
                'order_total' => $totalorder['total']+$order['order_shipping_price'],
                'order_nprod' => $totalorder['nprod'],
                'order_subtotal' => $totalorder['total'],
                'order_iva' => 21,
                ])
            ->update();
        }
        
        return $result;
    }
    #funcion que se encarga de crear un nuevo pedido
    public  function orderprocess($order_id)
    {   
            #incromentar id de pedido en 1
            $netx_id=$this->query('SELECT MAX(order_reference)+1 as id FROM `tbl_order`')->getRowArray();
            $this->table('tbl_order')->where('id',$order_id)->set(
                ['order_status' => 3,
                'order_reference' => $netx_id['id']??1,
                'created_order'=>date("Y-m-d H:i:s"),
                ''
                ])->update();
            $this->sendemailorder($order_id);
            return true;
        
    }
  
    public  function sendemailorder($order_id)
    {
        $order_details = $this->query('SELECT product_sku,product_name,product_price,product_quantity  FROM `tbl_order_details` WHERE order_id = '.(int)$order_id)->getResult('array');
       
        $order = $this->query("SELECT o.*,c.cif,c.firstname,c.lastname,c.company,c.country,c.city,c.address,c.phone,c.cp,c.email FROM `tbl_order`  as o
        JOIN `tbl_customer` as c ON o.`customer_id` = c.id WHERE o.id = " . (int)$order_id)->getRowArray();
       
        $tour_img = $this->query("SELECT * FROM `tbl_order_details` 
        JOIN `tbl_events` ON `tbl_events`.`id` = `tbl_order_details`.`events_id`
        JOIN `tbl_tour` ON `tbl_tour`.`id` = `tbl_events`.`tour_id` 
        JOIN `tbl_order` ON `tbl_order_details`.`order_id` = `tbl_order`.`id`
        WHERE `tbl_order`.`id` = " . (int)$order_id)->getRowArray();
        //$order = $this->query("SELECT * FROM `tbl_order` WHERE id=".(int)$order_id)->getRowArray();
        $html='<table border="0" width="100%">
        <tbody>
        <tr>
        <td>
            <table>
            <tbody>
            <tr >
            <td bgcolor="#fafafa" width="100%" colspan="2" valign="top"
                style="border-bottom:#555 2px solid;text-align: center;">
                <img border="0" alt="Latostadora"
                    src="https://easymerx.com/uploads/tours/original/'.$tour_img['img_e'].'"
                    style="margin: 5px auto;max-width: 568px;">
            </td> 
            </tr>
            <tr>
            <td valign="top" colspan="2" style="width: 569px;">&nbsp;</td>
            </tr>
            </tbody>
            </table>
        </td>
        </tr>
        </tbody>
        </table>';

        $html.='<h1 style="color:#333;font-family:Arial;font-size:20px">'. front_translate('General', 'hi') .', '.($order['order_company']!=''?$order['order_company']:$order['order_firstname']).'</h1><h3 style="color:#333;font-family:Arial;font-size:13px">'. front_translate('General', 'msj-budget') .'</h3><p style="color:#333;font-family:Arial;font-size:12px">'. front_translate('General', 'msj-budget1') .'</p>';
        $html.='<table border="0" width="100%" " style="width:100%;border-collapse:collapse;border-left:1px solid #d6d4d4;border-right:1px solid #d6d4d4;border-bottom:1px solid #d6d4d4;" bgcolor="#ffffff"><tr>
                    <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="22%" bgcolor="#f8f8f8">'. front_translate('General', 'product') .'</th>
                    <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="17%" bgcolor="#f8f8f8">'. front_translate('General', 'unit-price') .'</th>
                    <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" bgcolor="#f8f8f8">'. front_translate('General', 'quantity') .'</th>
                    <th style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="17%" bgcolor="#f8f8f8">'. front_translate('General', 'total-price') .'</th>
        </tr>';
        foreach ($order_details as $key => $c) {
            $html.='<tr>
                        <td  width="22%" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px">'.$c['product_name'].'</td>
                        <td width="17%" align="right" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px">'.number_format($c['product_price'],2,',','.').'€</td>
                        <td  align="center" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px">'.$c['product_quantity'].'</td>
                        <td width="17%"  align="right" style="color:#333;font-family:Arial;font-size:12px;padding-right:5px">'.number_format($c['product_price']*$c['product_quantity'],2,',','.').'€</td>
            </tr>';
        }
        $html.='</table>';
        $html.='<table border="0" width="100%">
        <tbody>
        <tr >
        <td align="right">
            <table border="0" width="300">
            <tbody>
            <tr >
            <td align="right" style="color:#333;font-family:Arial;font-size:13px;padding-right:5px">Gastos de envío</td>
            <td align="right" style="color:#333;font-family:Arial;font-size:13px;padding-right:5px">'.($order['order_subtotal']>0?number_format($order['order_shipping_price'],2,',','.').'€':'-').'</td>
            </tr>
            <tr >
            <td align="right" style="color:#333;font-family:Arial;font-size:18px;padding-right:5px;font-weight:bold">Total</td>
            <td align="right" style="color:#333;font-family:Arial;font-size:18px;padding-right:5px;font-weight:bold">'.($order['order_subtotal']>0?number_format($order['order_total'],2,',','.').'€':'-').'</td>
            </tr>
            </table>
        </td>
        </tr>
        </table>';
        $html.='<br/><br/><table border="0" width="100%" style="width:100%;border-collapse:collapse;border-top:1px solid #d6d4d4;border-left:1px solid #d6d4d4;border-right:1px solid #d6d4d4;border-bottom:1px solid #d6d4d4;" bgcolor="#ffffff">
        <tbody>
        <tr>
        <td style="padding-left:7px;color:#333;font-family:Arial;font-size:13px;">
            <p style="border-bottom:1px solid #d6d4d4;margin:3px 0 7px;font-weight:bold;font-size:15px;padding-bottom:10px;color:#333;font-family:Arial;">
            '. front_translate('General', 'budget-detail') .':</p>
            <b>Nº '. front_translate('General', 'budget') .':</b> '.$order['order_reference'].' <br>
            <b>'. front_translate('General', 'date') .' de '. front_translate('General', 'budget') .':</b> '.$order['updated_at'].' <br>
            <b>Método de envío:</b> '.(isset(self::METHODSHIPPING[$order['shipping_id']])?self::METHODSHIPPING[$order['shipping_id']]['name']:'-').' <br>
        </td>
        </tr>
        </tbody>
        </table>';
        $html.='<br/><br/><table border="0" width="100%" style="width:100%;border-collapse:collapse;border-top:1px solid #d6d4d4;border-left:1px solid #d6d4d4;border-right:1px solid #d6d4d4;border-bottom:1px solid #d6d4d4;" bgcolor="#ffffff">
        <tbody>
        <tr>
        <td style="padding-left:7px;color:#333;font-family:Arial;font-size:13px;">
            <p style="border-bottom:1px solid #d6d4d4;margin:3px 0 7px;font-weight:bold;font-size:15px;padding-bottom:10px;color:#333;font-family:Arial;">
            '. front_translate('General', 'my-data') .':</p>
            <b>'. front_translate('General', 'first-name') .':</b> '.$order['firstname'].' <br>
            <b>'. front_translate('General', 'last-name') .':</b> '.$order['lastname'].'<br>
            <b>Email:</b> '.$order['order_email'].'<br>
            <b>'. front_translate('General', 'country') .':</b> '.getCountryNameByISO($order['country']).' <br>
            <b>'. front_translate('General', 'city') .':</b> '.$order['city'].' <br>
            <b>'. front_translate('General', 'address') .':</b> '.$order['address'].' <br>
            <b>'. front_translate('General', 'phone') .':</b> '.$order['phone'].' <br>
            <b>CP:</b> '.$order['cp'].' <br>
        </td>
        </tr>
        </tbody>
        </table>';
        
        $email_subject='EasyMerx - Confirmación de tu pedido #'.$order['order_reference'];
        $emails_to=$order['order_email'];
        $cc=explode(',',trim(getConfiguration('WEB_CMAILORDER')));
        return send_email('themes/default/email/default',['message'=>$html],$emails_to,$email_subject,$cc);
    }
    public  function getorderitems($order_id)
    {
        return $this->query('
        SELECT od.*,round(od.product_quantity*od.product_price,2) as total,p.img,c.reference as comb_ref
        FROM `tbl_order_details`  od
        LEFT JOIN tbl_product as p ON  od.product_id=p.id
        INNER JOIN tbl_combinations as c ON od.combination_id = c.id
        WHERE  od.order_id = '.(int)$order_id)->getResult('array');
    }
    public static function getStatuses()
	{
		return self::STATUSES;
	}
    public static function getMethodpayment()
	{
		return self::METHODPAYMENT;
	}
    public static function getMethodshipping()
	{
		return self::METHODSHIPPING;
	}
    
    /*public static function getSelectcountry()
	{
		return self::SELECTCOUNTRY;
	}*/
    public static function getSelectcountry()
    {
        static $sortedCountries = null;
        if ($sortedCountries === null) {
            $countries = self::SELECTCOUNTRY;
            uasort($countries, function($a, $b) {
                return strcoll($a['name'], $b['name']);
            });
            $sortedCountries = $countries;
        }
        return $sortedCountries;
    }
   
}
