<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\BrandModel;
use App\Models\ProductImageModel;
use App\Models\ProductfeatureModel;
use App\Models\AttributesModel;
use App\Models\AttributesvalueModel;
use App\Models\CombinationsModel;
use App\Models\CombinationsvalueModel;
use App\Models\EventsModel;
use App\Models\PageModel;
use App\Models\ProductmodelsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class Products extends BaseController
{
    protected $productModel;
    protected $productmodelsModel;
    protected $categoryModel;
    protected $brandModel;
    protected $productImageModel;
    protected $productfeatureModel;
    protected $attributesModel;
    protected $attributesvalueModel;
    protected $combinationsModel;
    protected $combinationsvalueModel;
    protected $eventsModel;
    protected $pageModel;
    protected $perPage = 10;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->productmodelsModel = new ProductmodelsModel();
        $this->categoryModel = new CategoryModel();
        $this->brandModel = new BrandModel();
        $this->productImageModel = new ProductImageModel();
        $this->productfeatureModel = new ProductfeatureModel();
        $this->attributesModel = new AttributesModel();
        $this->attributesvalueModel = new AttributesvalueModel();
        $this->combinationsModel = new CombinationsModel();
        $this->combinationsvalueModel = new CombinationsvalueModel();
        $this->eventsModel = new EventsModel();
        $this->pageModel = new PageModel();
        $this->data['currentAdminMenu'] = 'products';
        $this->data['currentAdminSubMenu'] = 'products';
        $this->data['statuses'] = $this->productModel::getStatuses();
        $this->data['group_showsizes'] = $this->productModel::getGroupShowSize();
    }
    public function setcache()
	{
		if(getenv('cache.active') && getenv('cache.ttl') && getenv('cache.active')=='true'){
			if (!$categories = cache('cat_back')) {
				$categories=$this->categoryModel->get_categories(2,false);
				cache()->save('cat_back', $categories, getenv('cache.ttl'));
			}
            if (!$listcategories = cache('cat_list')) {
				$listcategories=$this->categoryModel->get_listcategories();
				cache()->save('cat_list', $listcategories, getenv('cache.ttl'));
			}
		}else{
			$categories=$this->categoryModel->get_categories(2,false);
            $listcategories=$this->categoryModel->get_listcategories();
		}
		$this->data['categories']=$categories;
        $this->data['listcategories']=$listcategories;
	}
    public function index()
    {
        $this->setcache();
        $this->data['s_categories']=$this->request->getVar('categories');
        $this->data['s_brand']=$this->request->getVar('s_brand');
        $this->data['s_events']=$this->request->getVar('s_events');
        $this->data['s_status']=$this->request->getVar('s_status');
        $this->data['s_name']=$this->request->getVar('s_name');

        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->productModel->getdata($nb_page=50,$page,$this->data['lang_id'],$this->data['s_name'],$this->data['s_categories'],$this->data['s_brand'],$this->data['s_status'],$this->data['s_events']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        $this->data['brands'] = $this->brandModel->listbrands();
        $this->data['events'] = $this->eventsModel->list();
        $html_cat='';
        if(is_array($this->data['s_categories']))
            foreach ($this->data['s_categories'] as $id => $v)
                if(isset($this->data['listcategories'][$id]))
                    $html_cat.='<span class="pstaggerTag">'.$this->data['listcategories'][$id]['name'].'</span>';
        $this->data['tagcategories'] = $html_cat;

        return view('admin/products/index', $this->data);
    }
    public function create()
    {
        $this->setcache();
        $this->data['brands'] = $this->brandModel->listbrands();
        $this->data['events'] = $this->eventsModel->list();
        $this->data['product'] = $this->productModel->setter(null,$this->data['languages']['codes']);
        $this->data['guiatallas'] = $this->pageModel->listpages(['group_id'=>2]);
        return view('admin/products/form', $this->data);
    }
    public function edit($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->setcache();
        $this->data['brands'] = $this->brandModel->listbrands();
        $this->data['guiatallas'] = $this->pageModel->listpages(['group_id'=>2]);
        $this->data['events'] = $this->eventsModel->list();
        $this->data['product'] = $this->productModel->setter($id,$this->data['languages']['codes']);
        $this->data['categoryIds'] = $this->productModel->catrelation($product['id']);
        $this->data['eventsIds'] = $this->productModel->eventrelation($product['id']);
        $html_cat='';
        foreach ($this->data['categoryIds'] as $id => $v)
            if(isset($this->data['listcategories'][$id]))
                $html_cat.='<span class="pstaggerTag">'.$this->data['listcategories'][$id]['name'].'</span>';
        $this->data['tagcategories'] = $html_cat;
        $this->data['tagcategoryd'] = isset($this->data['listcategories'][$product['id_category_default']])?'<span class="pstaggerTag">'.$this->data['listcategories'][$product['id_category_default']]['name'].'</span>':'';
        $this->data['productMenu'] = 'product_details';
        return view('admin/products/form', $this->data);
    }

    public function store()
    {

        $params = [
			'sku' => $this->request->getVar('sku'),
			'categories' => $this->request->getVar('categories'),
            'events' => $this->request->getVar('events'),
            'id_category_default' => $this->request->getVar('category'),
			'brand_id' => $this->request->getVar('brand_id'),
			'user_id' => $this->currentUser->id,
			'price' => $this->request->getVar('price'),
			'status' => $this->request->getVar('status'),
            'ean' => null,
			'stock' => $this->request->getVar('stock'),
            'minimal_quantity' => 1,
            'recommendation' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'position' => (int)$this->request->getVar('position'),
            'group_showsize' => $this->request->getVar('group_showsize'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                    'description_short' => $this->request->getVar("multilanguage")['description_short'],
                    'description' => $this->request->getVar("multilanguage")['description'],
                    'link_rewrite' => $this->request->getVar("multilanguage")['link_rewrite'],
                    'meta_title' => $this->request->getVar("multilanguage")['meta_title'],
                    'meta_keywords' => $this->request->getVar("multilanguage")['meta_keywords'],
                    'meta_description' => $this->request->getVar("multilanguage")['meta_description'],
                ]
        ];
        
        $this->db->transStart();
        $product_id=$this->productModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
        $product = $this->productModel->find($product_id);
        #asociar categorias
        $productcategories = $this->db->table('tbl_product_categories');
        if (!empty($params['categories'])) {
            foreach ($params['categories'] as $key => $categoryId) {
                $productcategories->insert([
                    'product_id' => $product['id'],
                    'category_id' => $categoryId,
                    'position' => 0,
                ]);
            }
        }
       #asociar events
       $productevents = $this->db->table('tbl_product_events');
       if (!empty($params['events'])) {
           foreach ($params['events'] as $key => $eventsId) {
               $productevents->insert([
                   'product_id' => $product['id'],
                   'events_id' => $eventsId,
                   'position' => 0,
               ]);
           }
       }
        $this->db->transComplete();
        
        if ($product) {
            $this->session->setFlashdata('success', 'Product has been saved.');
            return redirect()->to('/admin/products/' . $product['id'] .'/edit');
        } else {
            $this->data['categoryIds'] = $params['categories'];
            $this->data['errors'] = $this->productModel->errors();
            return view('admin/products/create', $this->data);
        }
    }

    public function update($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $params = [
			'id' => $id,
			'sku' => $this->request->getVar('sku'),
			'categories' => $this->request->getVar('categories'),
            'events' => $this->request->getVar('events'),
            'id_category_default' => $this->request->getVar('category'),
			'brand_id' => $this->request->getVar('brand_id'),
			'user_id' => $this->currentUser->id,
			'price' => $this->request->getVar('price'),
			'status' => $this->request->getVar('status'),
            'ean' => null,
			'stock' => $this->request->getVar('stock'),
			'minimal_quantity' => 1,
            'recommendation' => null,
            'updated_at' => date('Y-m-d H:i:s'),
            'position' => (int)$this->request->getVar('position'),
            'group_showsize' => $this->request->getVar('group_showsize'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                    'description_short' => $this->request->getVar("multilanguage")['description_short'],
                    'description' => $this->request->getVar("multilanguage")['description'],
                    'link_rewrite' => $this->request->getVar("multilanguage")['link_rewrite'],
                    'meta_title' => $this->request->getVar("multilanguage")['meta_title'],
                    'meta_keywords' => $this->request->getVar("multilanguage")['meta_keywords'],
                    'meta_description' => $this->request->getVar("multilanguage")['meta_description'],
                ]
        ];
      
        $this->db->transStart();
        $id=$this->productModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
        #asociar categorias
        $productcategories = $this->db->table('tbl_product_categories');
        $productcategories->where(['product_id' => $product['id']])->delete();
        if (!empty($params['categories'])) {
            foreach ($params['categories'] as $key => $categoryId) {
                $productcategories->insert([
                    'product_id' => $product['id'],
                    'category_id' => $categoryId,
                    'position' => 0,
                ]);
            }
        }
        
        #asociar events
       $productevents = $this->db->table('tbl_product_events');
       $productevents->where(['product_id' => $product['id']])->delete();
       if (!empty($params['events'])) {
           foreach ($params['events'] as $key => $eventsId) {
               $productevents->insert([
                   'product_id' => $product['id'],
                   'events_id' => $eventsId,
                   'position' => 0,
               ]);
           }
       }

        $this->db->transComplete();
        

        if ($this->productModel->errors()) {
            $this->data['categoryIds'] = $params['categories'];
            $this->data['errors'] = $this->productModel->errors();
            return view('admin/products/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Product has been saved.');
            return redirect()->to('/admin/products/' . $product['id'] .'/edit');
        }
    }
    public function destroy($id)
    {
        $product = $this->productModel->withDeleted()->find($id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if (empty($product['deleted_at'])) {
            //$this->productModel->delete($id);
            $this->db->query("DELETE FROM tbl_product WHERE id=".$id);
            $this->db->query("DELETE FROM tbl_product_categories WHERE product_id=".$id);
            $this->db->query("DELETE FROM tbl_product_images WHERE product_id=".$id);
            $this->db->query("DELETE FROM tbl_product_lang WHERE product_id=".$id);

            $this->session->setFlashdata('success', 'Product has been deleted.');
            return redirect()->to('/admin/products');
        } else {
            $this->db->table('product_categories')->where('product_id', $id)->delete();
            $this->productModel->delete($id, true);
            $this->session->setFlashdata('success', 'Product has been deleted permanently.');
            return redirect()->to('/admin/products/trashed');
        }
    }
  
    public function maintenance()
    {
        $products = $this->request->getVar('products');

        if ($this->request->getPost('btndelprods')!==null){
            if(isset($products) && is_array($products)){
                foreach ($products as $id){
                    $this->db->query("DELETE FROM tbl_product WHERE id=".$id);
                    $this->db->query("DELETE FROM tbl_product_categories WHERE product_id=".$id);
                    $this->db->query("DELETE FROM tbl_product_images WHERE product_id=".$id);
                    $this->db->query("DELETE FROM tbl_product_lang WHERE product_id=".$id);
                }
                $this->session->setFlashdata('success', 'Productos correctamente eliminados');
            }else{
                $this->session->setFlashdata('errors', 'Error, tienes que seleccionar productos');
            } 
         }

         if ($this->request->getPost('btnupprods')!==null){
                $m_categories = str_replace(' ','',$this->request->getVar('m_categories'));
                $m_category = trim($this->request->getVar('m_category'));
                $m_tipo = $this->request->getVar('m_tipo');
                $u_categories=$u_cats=$u_cat=[];
                if(isset($products) && is_array($products)){
                    $d_cat=$this->categoryModel->get_listcategories();
                    $error='';
                    if($m_categories!=''){
                        $m_categories=explode(',',$m_categories);
                        foreach ($m_categories as $id) 
                            if(isset($d_cat[$id])){
                                $u_categories[$id]=$id;
                            }else{
                                $error.='error categoría '.$id.' '.PHP_EOL; 
                            }
                    }
                    if($m_category!='')
                        if(!isset($d_cat[$m_category]))
                            $error.='error categoría '.$m_category.' '.PHP_EOL;
                    if($error==''){
                        if($m_tipo=='reemplazar'){
                            if(count($u_categories)>0){
                                foreach ($products as $id)
                                    foreach ($u_categories as $category_id)
                                        $u_cats[]=['product_id'=>$id,'category_id'=>$category_id,'position' => 0];
                                if(count($u_cats)){
                                    $this->db->table('tbl_product_categories')->whereIn('product_id',$products)->delete();
                                    $this->db->table('tbl_product_categories')->insertBatch($u_cats);
                                }
                            }
                            if($m_category!='' && isset($d_cat[$m_category])){
                                foreach ($products as $id)
                                    $u_cat[]=['id'=>$id,'id_category_default'=>$m_category];
                                if(count($u_cat))
                                    $this->productModel->updateBatch($u_cat, 'id');
                            }
                        }elseif($m_tipo=='combinar'){
                            if(count($u_categories)>0){
                                $pcdata=$this->db->table('tbl_product_categories')->whereIn('product_id',$products)->get()->getResultArray();
                                $re=[];
                                foreach ($pcdata as $k => $v) {
                                    $ke=$v['product_id'].'-'.$v['category_id'];
                                    $re[$ke]=$ke;
                                }
                                foreach ($products as $id)
                                    foreach ($u_categories as $category_id){
                                        $ke=$id.'-'.$category_id;
                                        if(!isset($re[$ke]))
                                            $u_cats[]=['product_id'=>$id,'category_id'=>$category_id,'position' => 0];
                                    }
                                if(count($u_cats)){
                                    $this->db->table('tbl_product_categories')->insertBatch($u_cats);
                                }
                            }
                            if($m_category!='' && isset($d_cat[$m_category])){
                                foreach ($products as $id)
                                    $u_cat[]=['id'=>$id,'id_category_default'=>$m_category];
                                if(count($u_cat))
                                    $this->productModel->updateBatch($u_cat, 'id');
                            }
                        }
                        $this->session->setFlashdata('success', 'Productos correctamente modificados');
                    }else{
                        $this->session->setFlashdata('errors', $error);
                    }
                }else{
                    $this->session->setFlashdata('errors', 'Error, tienes que seleccionar productos');
                }
        }
        return redirect()->back();
    }
   
    public function images($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
       
        $this->data['product'] = $this->productModel->setter($id,$this->data['languages']['codes']);
        $this->data['productImages'] = $this->productImageModel
            ->where('product_id', $product['id'])
            ->orderBy('position asc')
            ->findAll();
//print("<pre>");print_r($this->data['productImages']);exit;
        $this->data['productMenu'] = 'product_images';

        return view('admin/products/images', $this->data);
    }

    public function uploadImage($productId)
    {
        $product = $this->productModel->find($productId);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['product'] = $product;
        $this->data['imgopt'] = $this->request->getVar('imgopt')??'local';
        $this->data['productMenu'] = 'product_images';
        return view('admin/products/image_upload', $this->data);
    }

    public function doUploadImage($productId)
    {
        $product = $this->productModel->find($productId);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $imgopt = $this->request->getPost('imgopt');
        if($imgopt == 'local'){
            $validated = $this->validate([
                'image' => [
                    'uploaded[image]',
                    'mime_in[image,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[image,4096]',
                ],
            ]);
            if ($validated) {
                $image = $this->request->getFile('image');
                $fileName = $image->getRandomName();
                if($image->move('uploads/products/original/', $fileName)){
                        $images=generateImages(
                            $path='./uploads/products/',
                            $name=$fileName,
                            $sizes=$this->productImageModel::IMAGE_SIZES
                        );
                        $position = $this->productImageModel->getNewPosition($productId);
                        $params = [
                            'product_id' => $productId,
                            'img' => $fileName,
                            'position' => $position
                        ];
                    $this->productImageModel->save($params);
                    $this->productImageModel->setdefaultimg($productId);

                        $this->session->setFlashdata('success', 'Image has been saved.');
                        return redirect()->to('/admin/products/' . $productId . '/images');
                }
            }else{
                $this->session->setFlashdata('error', 'Image upload failed.');
                return redirect()->to('/admin/products/' . $productId . '/images');
            }
        } elseif ($imgopt == 'url') {
            $imageUrls = array_filter(array_map('trim', explode("\n", $this->request->getPost('imageurl'))));
            foreach ($imageUrls as $url) {
                if (filter_var($url, FILTER_VALIDATE_URL) && 
                    ($imageInfo = @getimagesize($url)) && 
                    in_array($imageInfo['mime'], ['image/jpeg', 'image/png', 'image/webp'])) {
                    
                    $fileName = uniqid() . '.' . pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $filePath = FCPATH . 'uploads/products/original/' . $fileName;
                    
                    if (($imageData = file_get_contents($url)) && file_put_contents($filePath, $imageData)) {
                        generateImages('./uploads/products/', $fileName, $this->productImageModel::IMAGE_SIZES);
                        $this->productImageModel->save([
                            'product_id' => $productId,
                            'img' => $fileName,
                            'position' => $this->productImageModel->getNewPosition($productId)
                        ]);
                    }
                }
            }
            
            $this->productImageModel->setdefaultimg($productId);
            $this->session->setFlashdata('success', 'Images have been saved.');
            return redirect()->to('/admin/products/' . $productId . '/images');
        }
       
    }

    public function destroyImage($id)
    {
        $image = $this->productImageModel->find($id);
        if (!$image) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->productImageModel->delete($id);
        $this->productImageModel->setdefaultimg($image['product_id']);
        $this->session->setFlashdata('success', 'Image has been deleted.');
        return redirect()->to('/admin/products/' . $image['product_id'] . '/images');
    }
    public function coverimagen($id)
    {
        $image = $this->productImageModel->find($id);
        if (!$image) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->productImageModel->where('product_id',$image['product_id'])->set(['cover'=>0])->update();
        $this->productImageModel->where('id',$image['id'])->set(['cover'=>1])->update();
        $this->productModel->where('id',$image['product_id'])->set(['img'=>$image['img']])->update();

        $this->session->setFlashdata('success', 'Image has been update.');
        return redirect()->to('/admin/products/' . $image['product_id'] . '/images');
    }
    public function positionimages($product_id)
    {
        $product = $this->productModel->find($product_id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $position = $this->request->getVar('position');
        if(is_array($position))
            foreach ($position as $img_id => $img_position)
                $this->productImageModel->where('id',$img_id)->set(['position'=>(int)$img_position])->update();
        $this->session->setFlashdata('success', 'Image has been update.');
        return redirect()->to('/admin/products/' . $product['id'] . '/images');
    }
    public function export()
    {
        ini_set ( 'max_execution_time', -1);
        $brands=$categories=[];
        $brands=$this->brandModel->listbrands();
        foreach ($this->db->query("SELECT category_id,name FROM tbl_category_lang where id_lang=1")->getResultArray() as $row)
                $categories[$row['category_id']]=$row['name'];
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle('categorias');
        $activeSheet->setCellValue('A1', 'Lista de productos');
        $activeSheet->setCellValue('A1', 'Fecha :'.date('d/m/Y') );
        $activeSheet->setCellValue('A3', 'ID');
        $activeSheet->setCellValue('B3', 'REFERENCIA');
        $activeSheet->setCellValue('C3', 'NOMBRE');
        $activeSheet->setCellValue('D3', 'MARCA');
        $activeSheet->setCellValue('E3', 'CATEGORIA');
        $activeSheet->setCellValue('F3', 'ID CATEGORIA');
        $activeSheet->setCellValue('G3', 'PRECIO');
        $activeSheet->setCellValue('H3', 'STOCK');
        $activeSheet->setCellValue('I3', 'ESTADO');
        $activeSheet->setCellValue('J3', 'IMAGEN');
        $activeSheet->setCellValue('K3', 'URL');
        $i=3;
        // Add some data
        $l='http://easymerx.com/producto/';
        foreach($this->db->query("
        SELECT p.id,p.sku,pl.name,p.brand_id,p.id_category_default,p.price,p.stock,p.status,p.img,pl.link_rewrite 
        FROM tbl_product as p left join tbl_product_lang as pl ON pl.product_id=p.id where pl.id_lang=1  limit 15000")->getResultArray() as $ra){
                $i++;
                $activeSheet
                ->setCellValue('A'.$i, $ra['id'])
                ->setCellValue('B'.$i, $ra['sku'])
                ->setCellValue('C'.$i, $ra['name'])
                ->setCellValue('D'.$i, (isset($brands[$ra['brand_id']])?$brands[$ra['brand_id']]:''))
                ->setCellValue('E'.$i, (isset($categories[$ra['id_category_default']])?$categories[$ra['id_category_default']]:''))
                ->setCellValue('F'.$i, $ra['id_category_default'])
                ->setCellValue('G'.$i, $ra['price'])
                ->setCellValue('H'.$i, $ra['stock'])
                ->setCellValue('I'.$i, (isset($this->data['statuses'][$ra['status']])?$this->data['statuses'][$ra['status']]:''))
                ->setCellValue('J'.$i, $ra['img'])
                ->setCellValue('K'.$i, $l.$ra['link_rewrite']);
        }
        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="productos_'.date('d-m-Y').'.xlsx"');
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
    public function features($id)
    {
        $product = $this->productModel->setter($id,$this->data['languages']['codes']);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

		$features=$this->productModel->getfeatures($id);
        $this->data['product'] = $product;
        $this->data['features'] = $features;
        $this->data['productMenu'] = 'product_features';
        return view('admin/products/features', $this->data);
    }
    public function setfeatures($product_id)
    {
        $product = $this->productModel->find($product_id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $feature = $this->request->getVar('feature');
        if(is_array($feature)){
            $this->productfeatureModel->where(['id_product'=>$product_id])->delete();
            $ifdata=[];
            foreach ($feature as $id_feature => $feature_values)
                if(is_array($feature_values))
                    foreach ($feature_values as $k => $id_feature_value)
                        $ifdata[] = ['id_feature' => $id_feature,'id_product'  => $product_id,'id_feature_value'  => $id_feature_value];

            if(count($ifdata))
               $this->productfeatureModel->insertBatch($ifdata);
        }
        $this->session->setFlashdata('success', 'Registro modificado correctamente');
        return redirect()->to('/admin/products/' . $product_id . '/features');
      
    }
    public function attributes($id)
    {
        $product = $this->productModel->setter($id,$this->data['languages']['codes']);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['product'] = $product;
        $this->data['productmodels'] = $this->productmodelsModel->findAll();
        $this->data['attributes'] = $this->productModel->getallattributes();
        $this->data['productattributes'] = $this->productModel->attributes($id);
        $this->data['productMenu'] = 'product_attributes';
        return view('admin/products/attributes', $this->data);
    }
    public function deleteCombination($combinationId)
{
    $combination = $this->combinationsModel->where('id',$combinationId)->first();
    // 1. Consulta las filas relacionadas en tbl_combinations_value
    $relatedRows = $this->combinationsvalueModel->where('combination_id', $combinationId)->findAll();

    // 2. Elimina las filas relacionadas en tbl_combinations_value
    foreach ($relatedRows as $relatedRow) {
        $this->combinationsvalueModel->delete($relatedRow['id']);
    }

    // 3. Elimina la combinación principal en tbl_combinations
    $this->combinationsModel->delete($combinationId);

    #seteamos el default_on=1 por defecto siempre en cuando no exista ninguna para el producto
    $this->combinationsModel->setDefaultOneCombination($combination['product_id']);

    // Puedes agregar un mensaje flash de éxito
    $this->session->setFlashdata('success', 'La combinación y las filas relacionadas se eliminaron correctamente.');

    // Redirige a la página de donde viniste
    return redirect()->back();
}

    public function setattributes($product_id)
    {
        $product = $this->productModel->find($product_id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $combinations= $this->request->getPost('selectedCombinations');
        $price = $this->request->getPost('price');
        $stock = $this->request->getPost('stock');
        $reference = $this->request->getPost('reference');
        $ean = $this->request->getPost('ean');
        $models_code = $this->request->getPost('models_code');
        $combinations = json_decode($combinations, true); // 'true' para obtener un arreglo asociativo
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error en el formato de las combinaciones.');
        }
        // Prepara los datos para la tabla 'tbl_combinations'
        $combinationData = [
            'product_id' => $product_id, // asegúrate de tener este valor desde tu contexto
            'price' => $price,
            'stock' => $stock,
            'reference' => $reference,
            'ean' => $ean, 
            'models_code' => $models_code,
            'default_on' => 0, 
        ];
        $combinationId = $this->combinationsModel->insertCombination($combinationData);
        foreach ($combinations as $row) { // asumiendo que $combinations es tu array de IDs de valores
            $valueData = [
                'combination_id' => $combinationId, 
                'attribute_id' => $row['attributeId'],
                'value_id' => $row['valueId'],
            ];
            $this->combinationsvalueModel->insertCombinationValue($valueData);
        }
        #seteamos el default_on=1 por defecto siempre en cuando no exista ninguna para el producto
        $this->combinationsModel->setDefaultOneCombination($product_id);

        $this->session->setFlashdata('success', 'Registro modificado correctamente');
        return redirect()->to('/admin/products/' . $product_id . '/attributes');
      
    }
    public function jsontools()
    {
        $act=$this->request->getVar('act');
        switch ($act) {
            case 'generatecombinationsbymodel_update':
                $product_id = (int)$this->request->getVar('product_id');
                $models = (int)$this->request->getVar('models');
                $precio = (float)$this->request->getVar('precio');
                $stock = (int)$this->request->getVar('stock');
                $sku = trim($this->request->getVar('sku'));
                $product = $this->productModel->where('id',$product_id)->first();
                $product_models = $this->productmodelsModel->where('id',$models)->first();
                #comprobar si existe tbl_combinations  default_on=1
                $combination_default_on = $this->combinationsModel->where(['product_id'=>$product_id,'default_on'=>1])->first()?true:false;
                $error_msg = $talla_id = [];
                if($product && $product_models && $precio > 0 && $stock > 0 && $sku != ''){
                    $color = trim($product_models['color']);#negro (solo una talla)
                    $tallas = trim($product_models['talla']);#S,M,L,XL,XXL,3XL (muchas tallas)
                    #comprobar que existe el color y extraer el id
                    $attribute_id_color = 3; #color
                    $lang_id = 1; # idioma español
                    $color = $this->attributesvalueModel->searchByName($color,$attribute_id_color,$lang_id);
                    if(!$color){
                        $error_msg[]='El color no existe '.$color;
                    }else{
                        $color_id = $color['attribute_value_id'];
                    }
                    $attribute_id_talla = 2; #talla
                    #hacer explode y eliminar vacios y hacer trim  
                    $data_tallas = array_filter(explode(',',$tallas), function($item){
                        return trim($item) !== '';
                    });
                    foreach($data_tallas as $talla){
                        $talla = $this->attributesvalueModel->searchByName($talla,$attribute_id_talla,$lang_id);
                        if(!$talla){
                            $error_msg[]='La talla no existe '.$talla;
                        }else{
                            $talla_id[] = ['attribute_value_id'=>$talla['attribute_value_id'],'attribute_value_name'=>$talla['attribute_value_name']];
                        }
                    }
                    if (empty($error_msg)){
                        #crear para el color y cada una de  las  tallas la combinacion

                        foreach ($talla_id as $k => $talla) {
                            $combinationData = [
                                'product_id' => $product_id,
                                'price' => $precio,
                                'stock' => $stock,
                                'reference' => $sku.'-'.trim($product_models['code']).'-'.$talla['attribute_value_name'],
                                'ean' => '',
                                'models_code' => trim($product_models['code']),
                                'default_on' => isset($talla['attribute_value_name']) && $talla['attribute_value_name']=='L' && $combination_default_on==false?1:0,
                            ];
                            $this->combinationsModel->insert($combinationData);
                            $combinationId = $this->db->insertID();
                            #insertar en las tablas tbl_combinations_value
                            $this->combinationsvalueModel->insert([
                                'combination_id' => $combinationId,
                                'attribute_id' => 2, #talla
                                'value_id' => $talla['attribute_value_id'],
                            ]);
                            #insertamos el color
                            $this->combinationsvalueModel->insert([
                                'combination_id' => $combinationId,
                                'attribute_id' => 3, #color
                                'value_id' => $color_id,
                            ]);
                        }
                        $result = [
                            'error' => false,
                            'msg' => 'Los datos se han guardado correctamente.',
                            'data' => []
                        ];
                    }else{
                        $result = [
                            'error' => true,
                            'msg' => implode('<br>', $error_msg),
                            'data' => []
                        ];
                    }
                    #seteamos el default_on=1 por defecto siempre en cuando no exista ninguna para el producto
                    $this->combinationsModel->setDefaultOneCombination($product_id);
                }else{
                    $result = [
                        'error' => true,
                        'msg' => 'No se pudieron guardar los cambios. Inténtalo de nuevo.',
                        'data' => []
                    ];
                }
            break;
            case 'getattributes':
                $error = '';
                $product_id=(int)$this->request->getVar('product_id');
                $combination_id=(int)$this->request->getVar('combination_id');
                $combination= $this->productModel->attributes($product_id,$combination_id);
                $result = [
                    'error' => false,
                    'msg' => '',
                    'data' => isset($combination[0])?$combination[0]:[]
                ];
            break;
            case 'setattributes':
                // Obtén los datos del formulario
                $product_id = (int)$this->request->getVar('product_id');
                $combination_id = (int)$this->request->getVar('combination_id');
                $models_code = (string)$this->request->getVar('models_code');
                $default_on = (int)$this->request->getVar('default_on');

                // Inicia una transacción de base de datos
                $this->db->transStart();

                try {
                    // Verifica si ya existe una combinación con default_on = 1 para el mismo product_id
                    if ($default_on === 1) {
                        $this->combinationsModel->resetDefaultCombination($product_id);
                    }

                    // Actualiza la combinación con los nuevos datos
                    $params = [
                        'price' => $this->request->getVar('precio'),
                        'stock' => $this->request->getVar('stock'),
                        'reference' => $this->request->getVar('referencia'),
                        'ean' => $this->request->getVar('ean'),
                        'models_code' => $models_code,
                        'default_on' => $default_on,
                    ];
                    $this->combinationsModel->saveAttributes($combination_id, $product_id, $params);

                    // Confirma la transacción
                    $this->db->transComplete();

                    $result = [
                        'success' => true,
                        'msg' => 'Los datos se han guardado correctamente.',
                        'data' => '',
                    ];
                } catch (\Exception $e) {
                    // Si ocurre algún error, revierte la transacción
                    $this->db->transRollback();

                    $result = [
                        'success' => false,
                        'msg' => 'No se pudieron guardar los cambios. Inténtalo de nuevo.',
                        'data' => '',
                    ];
                }

                // Finaliza la transacción
                $this->db->transComplete();
                #seteamos el default_on=1 por defecto siempre en cuando no exista ninguna para el producto
                $this->combinationsModel->setDefaultOneCombination($product_id);
            break;
            default:
                $result = [
                    'error' => true,
                    'msg' => 'error',
                    'data' => ''
                ];
                break;
        }
       return $this->response->setJSON($result);
    }
}