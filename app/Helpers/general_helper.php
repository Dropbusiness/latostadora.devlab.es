<?php
use Config\Services;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
function set_cart_id() {
    $cart_id = get_cookie('cart_id');
    if (!$cart_id) {
        $cart_id = uniqid('cart_', true);
        set_cookie('cart_id', $cart_id, 86400 * 10);
    }
    return $cart_id;
}
function generateUuid(): string
{
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Versión 4
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variante RFC 4122
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
function base_url_locale(string $uri = ''): string
    {
        $request = service('request');
        $locale = $request->getLocale();
        $defaultLocale = 'es';
        if ($locale === $defaultLocale)
            $locale = '';
        return base_url(($locale ? $locale . '/' : '') . $uri);
    }
function setlogs($params){
    $request = Services::request();
    $agent = $request->getUserAgent();
    $logs = new \App\Models\LogsModel();
    $userlog = [
        'date'	=> date("Y-m-d"),
        'time'	=> date("H:i:s"),
        'target'	=> isset($params['target'])?$params['target'] :'general',
        'id_lang'	=> isset($params['id_lang'])?$params['id_lang'] :1,
        'reference'	=> isset($params['reference'])?$params['reference'] :'',
        'name'	=> isset($params['name'])?$params['name'] :'',
        'ip'	=> $request->getIPAddress(),
        'browser'	=> $agent->getBrowser(),
        'status'	=> isset($params['status'])?$params['status'] :1,
    ];
    return $logs->save($userlog);
}

#idiomas
function front_translate($file, $text = null,array $args = [], ?string $locale = null){
    if (!is_null($text)){
        return lang('Front/' . $file . '.text.' . $text,$args,$locale);
    }
    return lang('Front/' . $file . '.text.',$args,$locale);
}
function admin_translate($file, $text = null){
    if (!is_null($text)){
        return lang('Admin/' . $file . '.text.' . $text);
    }
    return lang('Admin/' . $file . '.text.');
}
function bo_language($used = false, $status = 1)
{
    $model = new \App\Models\LanguageModel();
    $locale = service('request')->getLocale();

    if (is_null($status)){
        return $model->findAll();
    }

    if ($used){
        return $model->where('status',$status)->where('code', $locale)->first();
    }
    return $model->where('status',$status)->findAll();
}
function input_multilanguage($params = [])
{
    $dropdown='';
    $inputs='';
    foreach ($params['languages'] as $k => $v){
        $dropdown.='<span class="dropdown-item js-locale-item" data-locale="'.$v['code'].'" onclick="changelanguage(this)">'.$v['name'].' <img alt="image" src="/uploads/languages/'.$v['code'].'.svg" class="ml-1" width="18"></span>';
        if(isset($params['type']) && $params['type']=='textarea'){
            $input='<textarea id="multilanguage_'.$params['name'].'_'.$v['id'].'" name="multilanguage['.$params['name'].']['.$v['id'].']" class="'.(isset($params['class'])?$params['class']:'form-control editorhtml').'" rows="'.(isset($params['rows'])?$params['rows']:'10').'" cols="50"  '.(isset($params['required']) && $params['required']==true?'required':'').'>'.(isset($params['values'][$v['id']])?$params['values'][$v['id']]:'').'</textarea>';
        }else{
            $input='<input type="text" id="multilanguage_'.$params['name'].'_'.$v['id'].'" name="multilanguage['.$params['name'].']['.$v['id'].']" class="'.(isset($params['class'])?$params['class']:'form-control').'"  value="'.(isset($params['values'][$v['id']])?$params['values'][$v['id']]:'').'" '.(isset($params['required']) && $params['required']==true?'required':'').'>';
        }
        $inputs.='<div class="js-locale-input js-locale-'.$v['code'].' '.($v['code']!=$params['lang']?'d-none':'').'">'.$input.'</div>';
    }
    $html='
    <div class="form-group">
        <label class="form-control-label">'.(isset($params['required']) && $params['required']==true?'<span class="text-danger">*</span>':'').' '.$params['label'].'  
                <div class="dropdown d-inline">
                    <a class="dropdown-toggle js-locale-btn" type="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="multilanguage_'.$params['name'].'"><img alt="image" src="/uploads/languages/'.$params['lang'].'.svg" class="ml-1" width="18"></a>
                    <div class="dropdown-menu locale-dropdown-menu" aria-labelledby="multilanguage_'.$params['name'].'">'.$dropdown.'</div>
                </div>
        </label>
        <div class="input-container">
            <div class="locale-input-group js-locale-input-group">'.$inputs.'</div>
            '.(isset($params['text'])?'<small class="form-text">'.$params['text'].'</small>':'').'
        </div>
    </div>
    ';
    return $html;
}


function treeCategories($categories,$id='',$selected='')
{
    $html = '<ul class="'.($id!=''?'collapsed':'').'">';
    foreach($categories as $category){
        $not_sub=empty($category['sub']);
        $html .= '<li><div class="checkbox itemcatt">'.(!$not_sub?'<i class="caret-cat fa fa-plus"></i>':'<i class="fa notsub"></i>').'<input  id="radio-'.$category['id'].'" class="ckcategories" name="parent_id" data-id="'.$category['id'].'" data-name="'.$category['name'].'" value="'.$category['id'].'" type="radio" '.($selected==$category['id']?'checked':'').'> '.$category['name'].'</div>';
        if(!$not_sub)
            $html .= treeCategories($category['sub'],$category['id'],$selected);
         $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
function treeCatProd($categories,$id='',$selected=[],$principal='')
{
    $html = '<ul class="'.($id!=''?'collapsed':'').'">';
    foreach($categories as $category){
        $not_sub=empty($category['sub']);
        $html .= '<li><div class="checkbox itemcatp">'.(!$not_sub?'<i class="caret-cat fa fa-plus"></i>':'<i class="fa notsub"></i>').'<input id="checkbox-'.$category['id'].'" name="categories[]" data-id="'.$category['id'].'" data-name="'.$category['name'].'"  value="'.$category['id'].'" type="checkbox" '.(isset($selected[$category['id']])?'checked':'').'> '.$category['name'].' <input type="radio" id="radio-'.$category['id'].'" value="'.$category['id'].'" name="category" class="dfcategory" '.($principal==$category['id']?'checked':'').'></div>';
        if(!$not_sub)
            $html .= treeCatProd($category['sub'],$category['id'],$selected,$principal);
         $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
function treeCatFilter($categories,$id='',$selected=[])
{
    $html = '<ul class="'.($id!=''?'collapsed':'').'">';
    foreach($categories as $category){
        $not_sub=empty($category['sub']);
        $html .= '<li><div class="checkbox itemcatp">'.(!$not_sub?'<i class="caret-cat fa fa-plus"></i>':'<i class="fa notsub"></i>').'<input id="checkbox-'.$category['id'].'" name="categories['.$category['id'].']" data-id="'.$category['id'].'" data-name="'.$category['name'].'"  value="'.$category['id'].'" type="checkbox" '.(isset($selected[$category['id']])?'checked':'').'> '.$category['name'].'  <b class="small">(ID:'.$category['id'].')</b></div>';
        if(!$not_sub)
            $html .= treeCatFilter($category['sub'],$category['id'],$selected);
         $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
function generateImages($originalPath,$name,$sizes){
    try {
        $manager = new \Intervention\Image\ImageManager(array('driver' => 'imagick'));
        $fileimg=$originalPath.'/original/'.$name;
        foreach ($sizes as $size => $sizeDetails) {
            $newfileimg=$originalPath.'/'.$size.'/'.$name;
            $manager->make($fileimg)->resize($sizeDetails['width'], $sizeDetails['height'], function ($constraint) {
                $constraint->aspectRatio();
            })->resizeCanvas($sizeDetails['width'], $sizeDetails['height'], 'center', false)->save($newfileimg);
        }
    } catch (Throwable $t) {
        return false;
    }
    return true;
}

function slugify($string, $replace = array(), $delimiter = '-') {
    $oldLocale = setlocale(LC_ALL, '0');
    setlocale(LC_ALL, 'en_US.UTF-8');
    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    if (!empty($replace))
      $clean = str_replace((array) $replace, ' ', $clean);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower($clean);
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
    $clean = trim($clean, $delimiter);
    setlocale(LC_ALL, $oldLocale);
    return $clean;
  }

function truncate($string, $length, $dots = "...") {
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}
function featuretohtml($feature){
    $items=explode(PHP_EOL,$feature);
    $html='<ul>';
    foreach ($items as $item) {
        $i=explode('|',$item);
        if(isset($i[0]) && isset($i[1])){
            $html.='<li><span>'.$i[0].'</span>: '.$i[1].'</li>';
        }
    }
    $html.='</ul>';
    return $html;
}

function priceformat($price,$simbolo='€'){
    return number_format((float)$price,2,',','.').$simbolo;
}

function password_generate($chars=8) 
{
  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
  return substr(str_shuffle($data), 0, $chars);
}
function parsehtml($html, $braces){
        $template = $html;
        foreach($braces as $brace => $replacement){
            $brace = trim($brace);
            $template = str_replace($brace, $replacement, $template);
        }
        return $template;
}
function encrypt_text($dato) 
{ 
    $resultado = $dato;
    $arrayLetras = array('W', 'S');
    $limite = count($arrayLetras) - 1;
    $num = mt_rand(0, $limite);
    for ($i = 1; $i <= $num; $i++) {
        $resultado = base64_encode($resultado);
    }
    $resultado = $resultado . '+' . $arrayLetras[$num];
    $resultado = base64_encode($resultado);
    return $resultado;
}
function decrypt_text($dato) 
{ 
    $resultado = base64_decode($dato);
    @list($resultado, $letra) = explode('+', $resultado);
    $arrayLetras = array('W', 'S');
    for ($i = 0; $i < count($arrayLetras); $i++) {
        if ($arrayLetras[$i] == $letra) {
            for ($j = 1; $j <= $i; $j++) {
                $resultado = base64_decode($resultado);
            }
            break;
        }
    }
    return $resultado;
}
function cleartxt($texto) {
    return preg_replace('/[^a-zA-Z0-9\sñáéíóúÁÉÍÓÚÑ]/u', '', $texto);
}

function send_email_OLD($theme='themes/default/email/default',$params=[],$to=[],$subject='',$cc=[])
{
    $message = view($theme,$params);
    $email = \Config\Services::email();
    $config['protocol'] = getenv('email_config_protocol');
    $config['SMTPHost'] =  getenv('email_config_SMTPHost');
    $config['SMTPUser'] =  getenv('email_config_SMTPUser');
    $config['SMTPPass'] =  getenv('email_config_SMTPPass');
    $config['SMTPPort'] =  getenv('email_config_SMTPPort');
    $config['SMTPCrypto'] =  getenv('email_config_SMTPCrypto');
    $config['mailType'] = 'html';
    $email->initialize($config);
    $email->setTo($to);
    if(is_array($cc) && count($cc))
        $email->setCC($cc);
    $email->setFrom('no-reply@easymerx.com', 'EasyMerx');
    $email->setReplyTo('ayuda@easymerx.com');
    $email->setSubject($subject);
    $email->setMessage($message);
    if ($email->send())
    {
        return true;
    }
    else
    {
        $data = $email->printDebugger(['headers']);
        return false;
    }
}

function get_curldata($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data,true);
}

function badge_typeorders($state) {
    switch ($state) {
        case 2:
            $html='<span class="badge rounded-pill text-bg-t2 ">Propuesta carta</span>';
            break;
        case 4:
            $html='<span class="badge rounded-pill text-bg-t4 ">Cotización de Vinos</span>';
            break;
        case 6:
            $html='<span class="badge rounded-pill text-bg-t6 ">Análisis de Copas</span>';
            break;
        default:
            $html='<span class="badge rounded-pill text-bg-t2 ">-</span>';
            break;
    }
    return $html;
}
function badge_orders($state) {
    switch ($state) {
        case 0:
            $html='<span class="badge rounded-pill text-bg-s0 ">Eliminado</span>';
            break;
        case 1:
            $html='<span class="badge rounded-pill text-bg-s4 ">En proceso</span>';
            break;
        case 2:
            $html='<span class="badge rounded-pill text-bg-s2 ">Guardado</span>';
            break;
        case 3:
            $html='<span class="badge rounded-pill text-bg-s3 ">Confirmado</span>';
            break;
        case 4:
            $html='<span class="badge rounded-pill text-bg-s4 ">En proceso</span>';
            break;
        case 5:
            $html='<span class="badge rounded-pill text-bg-s5 ">Error</span>';
            break;
        default:
            $html='<span class="badge rounded-pill text-bg-s5 ">-</span>';
            break;
    }
    return $html;
}
function get_event_details(int $event_id): ?array
{
        $db = \Config\Database::connect(); // Conexión a la base de datos

        // Consulta a la tabla tbl_events
        $query = $db->table('tbl_events')
                    ->select('tbl_events.tour_id, tbl_events.slug, tbl_events.date, tbl_tour.slug as tour_slug, tbl_artists.slug as artist_slug')
                    ->join('tbl_tour', 'tbl_tour.id = tbl_events.tour_id', 'left') // JOIN con tbl_tour
                    ->join('tbl_artists', 'tbl_artists.id = tbl_events.artists_id', 'left') // JOIN con tbl_artist
                    ->where('tbl_events.id', $event_id) // Filtro por event_id
                    ->get();

        $result = $query->getRowArray(); // Obtener una fila como array

        return $result ?: null; // Devuelve el resultado o null si no existe
}
function getConfiguration($key,$default=''){
    $model = new \App\Models\ConfigurationModel();
    $config=$model->where(['name'=>$key])->first();
    return isset($config['value'])?$config['value']:$default;
}
function getCountryNameByISO($isoCode)
{
    $countries = (new \App\Models\OrderModel())::SELECTCOUNTRY;
    foreach ($countries as $country) {
        if ($country['iso'] === $isoCode) return $country['name'];
    }
    return '';
}
function get_value_from_data($data, string $field)
    {
        // Decodificar JSON si $data es una cadena
        if (is_string($data)) {
            $data = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return null; // Devuelve null si no es un JSON válido
            }
        }

        // Verificar si el primer argumento es un array
        if (!is_array($data)) {
            return null; // Devuelve null si no es un array
        }

        // Manejar claves anidadas con notación de puntos
        $keys = explode('.', $field);
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                return null; // Devuelve null si no encuentra la clave
            }
        }

        return $data;
    }
/**
 * Envía por email los pedidos agrupados por tour en formato Excel
 * Solo procesa tours con custom_sendemail=1 y custom_email válido
 * Solo incluye pedidos con order_status=3
 * 
 * @param array $params Parámetros adicionales (opcional)
 * @return array Resultado del procesamiento con estadísticas
 */
function sendorder_tours_custom($params = [])
{
    #log guardamos inicio 
    file_put_contents('./uploads/log/webservice.txt',date('Y-m-d H:i:s').') sendorder_tours_custom [INICIO]:'.json_encode($params).PHP_EOL, FILE_APPEND);
    $db = \Config\Database::connect();
    
    try {
        // Obtener tours activos con configuración de email personalizada
        $toursQuery = $db->query("
            SELECT 
                t.id as tour_id,
                tl.name,
                t.slug,
                t.custom_email,
                t.custom_contact
            FROM tbl_tour t
            INNER JOIN tbl_tour_lang tl ON tl.tour_id = t.id and tl.id_lang=1
            WHERE t.custom_sendemail = 1 
            AND t.custom_email IS NOT NULL 
            AND t.custom_email != ''
            AND t.status = 1
        ");
        
        $tours = $toursQuery->getResultArray();
       
        if (empty($tours)) {
            return [
                'success' => true,
                'message' => 'No hay tours configurados para envío',
                'tours_processed' => 0,
                'emails_sent' => 0
            ];
        }
        
        $emailsSent = 0;
        $toursProcessed = 0;
        $errors = [];
        
        foreach ($tours as $tour) {
            try {
                // Obtener pedidos del tour con status 3 (completados)
                $queryBase = "
                    SELECT DISTINCT
                        o.id as order_id,
                        o.order_reference,
                        o.created_order as fecha_pedido,
                        c.firstname,
                        c.lastname,
                        c.address,
                        c.cif,
                        c.country,
                        c.city,
                        c.cp,
                        od.product_name,
                        od.product_price,
                        od.product_quantity,
                        (od.product_price * od.product_quantity) as total_producto
                    FROM tbl_order o
                    INNER JOIN tbl_customer c ON o.customer_id = c.id
                    INNER JOIN tbl_order_details od ON o.id = od.order_id
                    WHERE od.tour_id = ? 
                    AND o.order_status = 3
                ";
                
                $queryParams = [$tour['tour_id']];
                
                // Agregar filtros de fecha si existen en los parámetros
                if (isset($params['created_order_at_start']) && !empty($params['created_order_at_start'])) {
                    $queryBase .= " AND o.created_order >= ?";
                    $queryParams[] = $params['created_order_at_start'];
                }
                
                if (isset($params['created_order_at_end']) && !empty($params['created_order_at_end'])) {
                    $queryBase .= " AND o.created_order <= ?";
                    $queryParams[] = $params['created_order_at_end'];
                }
                
                $queryBase .= " ORDER BY o.created_order DESC, o.order_reference DESC";
                
                $ordersQuery = $db->query($queryBase, $queryParams);
                $orders = $ordersQuery->getResultArray();
                //echo "sql<pre>".(string)$db->getLastQuery()."</pre>";exit;
                if (empty($orders)) {
                    continue;
                }
                
                // Generar archivo Excel
                $excelFile = null;
                $excelFile = generateTourOrdersExcel($orders, $tour);
                
                if ($excelFile) {
                    // Preparar datos para el email
                    $tourName = $tour['name'];
                    $subject = "Resumen de pedidos - Tour: " . $tourName;
                    $message = "<p>Adjuntamos el resumen de pedidos del tour " . $tourName . " con " . count($orders) . " pedidos.</p>";
                    $emails=array_map('trim',array_unique(explode(',',$tour['custom_email'])));
                    #para el id tour 10
                    #if($tour['tour_id']==8)
                       # $emails=['avargas@dropbusiness.es'];
                    // Enviar email con adjunto
                    $emailSent = send_email(
                        'themes/default/email/default',
                        ['message' => $message],
                        [],
                        $subject,
                        [],
                        $excelFile,
                        $emails
                    );
                    
                    if ($emailSent) {
                        $emailsSent++;
                        
                        // Actualizar fecha de último envío
                        $db->query("
                            UPDATE tbl_tour 
                            SET custom_lastdatetime_sendemail = NOW() 
                            WHERE id = ?
                        ", [$tour['tour_id']]);

                        #log guardamos fin 
                        file_put_contents('./uploads/log/webservice.txt',date('Y-m-d H:i:s').') sendorder_tours_custom [SEND MAIL][TOUR-'.$tour['tour_id'].']:'.json_encode($emails).PHP_EOL, FILE_APPEND);
                    } else {
                        $errors[] = "Error enviando email para tour: {$tour['slug']}";
                    }
                    // Limpiar archivo temporal
                    if (file_exists($excelFile)) {
                        unlink($excelFile);
                        unset($excelFile);
                    }
                }
                $toursProcessed++;
                
            } catch (\Exception $e) {
                $errors[] = "Error procesando tour {$tour['slug']}: " . $e->getMessage();
                log_message('error', "Error en sendorder_tours_custom para tour {$tour['slug']}: " . $e->getMessage());
            }
        }
        
        return [
            'success' => true,
            'message' => 'Procesamiento completado',
            'tours_processed' => $toursProcessed,
            'emails_sent' => $emailsSent,
            'errors' => $errors
        ];
        
    } catch (\Exception $e) {
        log_message('error', 'Error en sendorder_tours_custom: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error general: ' . $e->getMessage(),
            'tours_processed' => 0,
            'emails_sent' => 0
        ];
    }
}
/**
 * Genera archivo Excel con los pedidos del tour
 * 
 * @param array $orders Pedidos del tour
 * @param array $tour Información del tour
 * @return string|false Ruta del archivo generado o false en caso de error
 */
function generateTourOrdersExcel($orders, $tour)
{
    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar encabezados
        $headers = [
            'A1' => 'Fecha pedido',
            'B1' => 'Nombre cliente',
            'C1' => 'Ciudad cliente', 
            'D1' => 'CP cliente',
            'E1' => 'País cliente',
            'F1' => 'Nombre producto',
            'G1' => 'Precio/ud producto',
            'H1' => 'Cantidad',
            'I1' => 'Total producto'
        ];
        
        // Establecer encabezados
        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }
        
        // Estilo para encabezados
        $headerStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8E8E8']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'font' => [
                'bold' => true
            ]
        ];
        
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
        
        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(8);
        $sheet->getColumnDimension('F')->setWidth(50);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(15);
        
        // Llenar datos
        $row = 2;
        foreach ($orders as $order) {
            // Sanitizar nombre - solo 7 primeros caracteres
            $nombreCompleto = trim(($order['firstname'] ?? '') . ' ' . ($order['lastname'] ?? ''));
            $nombreSanitizado = substr($nombreCompleto, 0, 7) . (strlen($nombreCompleto) > 7 ? '...' : '');
            
            
            $sheet->setCellValue('A' . $row, $order['fecha_pedido']);
            $sheet->setCellValue('B' . $row, $nombreSanitizado);
            $sheet->setCellValue('C' . $row, $order['city']);
            $sheet->setCellValue('D' . $row, $order['cp']);
            $sheet->setCellValue('E' . $row, getCountryNameByISO($order['country'])); // Solo código ISO
            $sheet->setCellValue('F' . $row, $order['product_name']);
            $sheet->setCellValue('G' . $row, $order['product_price']);
            $sheet->setCellValue('H' . $row, $order['product_quantity']);
            $sheet->setCellValue('I' . $row, $order['total_producto']);
            
            $row++;
        }
        
        // Aplicar bordes a toda la tabla
        $tableRange = 'A1:I' . ($row - 1);
        $sheet->getStyle($tableRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        
        // Formatear columnas numéricas
        $sheet->getStyle('G2:G' . ($row - 1))->getNumberFormat()
              ->setFormatCode('#,##0.00"€"');
        $sheet->getStyle('I2:I' . ($row - 1))->getNumberFormat()
              ->setFormatCode('#,##0.00"€"');
        
        // Generar nombre de archivo temporal
        //la fecha es de ayer
        $fileName = 'pedidos_' . $tour['slug'] . '_' . date('Y-m-d', strtotime('-1 day')) . '.xls';
        $filePath = WRITEPATH . 'uploads/temp/' . $fileName;
        
        // Crear directorio si no existe
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        // Guardar archivo
        $writer = new Xls($spreadsheet);
        $writer->save($filePath);
        
        // Limpiar memoria
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $writer, $sheet);

        return $filePath;
        
    } catch (\Exception $e) {
        log_message('error', 'Error generando Excel: ' . $e->getMessage());
        return false;
    }
}
function send_email($theme = 'themes/default/email/default', $params = [], $to = [], $subject = '', $cc = [], $attachment = null,$bcc=[])
{
    $message = view($theme, $params);
    $email = \Config\Services::email();
    $config['protocol'] = getenv('email_config_protocol');
    $config['SMTPHost'] = getenv('email_config_SMTPHost');
    $config['SMTPUser'] = getenv('email_config_SMTPUser');
    $config['SMTPPass'] = getenv('email_config_SMTPPass');
    $config['SMTPPort'] = getenv('email_config_SMTPPort');
    $config['SMTPCrypto'] = getenv('email_config_SMTPCrypto');
    $config['mailType'] = 'html';
    $email->initialize($config);
    if ((is_array($to) && count($to)) || is_string($to))
        $email->setTo($to);
    if ((is_array($cc) && count($cc)) || is_string($cc))
        $email->setCC($cc);
    if ((is_array($bcc) && count($bcc)) || is_string($bcc))
        $email->setBCC($bcc);
    $email->setFrom('no-reply@easymerx.com', 'EasyMerx');
    $email->setReplyTo('ayuda@easymerx.com');
    $email->setSubject($subject);
    $email->setMessage($message);
    
    // Adjuntar archivo si se proporciona
    if ($attachment && file_exists($attachment)) {
        $email->attach($attachment);
    }
    
    if ($email->send()) {
        // Limpieza explícita
        $email->clear(true);
        unset($email, $message);

        return true;
    } else {
        $data = $email->printDebugger(['headers']);
        return false;
    }
}