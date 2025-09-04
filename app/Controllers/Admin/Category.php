<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\CategoryModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class Category extends BaseController
{
    protected $categoryModel;
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->data['currentAdminMenu'] = 'products';
        $this->data['currentAdminSubMenu'] = 'categories';
        $this->data['statuses'] = $this->categoryModel::getStatuses();
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
    public function add()
    {
        $this->setcache();
        $this->data['data'] = $this->categoryModel->setter(null,$this->data['languages']['codes']);
        return view('admin/category/form', $this->data);
    }

    public function index($parent_id=18)
    {
        //$this->generateallimg();
        $this->setcache();
        $this->data['data'] = $this->categoryModel->find($parent_id);
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->categoryModel->getdata($nb_page=30,$page,$this->data['lang_id'],$parent_id,$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        /*breadcrumb*/
		$categorienav='<a href="/admin/category/">Catálogo</a>';
		$parents=explode(',',$this->data['data']['parents']);
		foreach ($parents as $id)
			if(isset($this->data['listcategories'][$id]))
                $categorienav.=" > <a href='/admin/category/".$id."'>".$this->data['listcategories'][$id]['name'].'</a>';
        $this->data['categorienav'] = $categorienav;

        return view('admin/category/index', $this->data);
    }

    public function save()
    {
        $params = [
			'id' => $this->request->getVar('id'),
			'status' => $this->request->getVar('status'),
            'parent_id' => $this->request->getVar('parent_id'),
            'position' => $this->request->getVar('position'),
            'created_at' => date('Y-m-d H:i:s'),
            'multilanguage' => 
                [
                    'name'          => $this->request->getVar("multilanguage")['name'],
                    'description_short'   => $this->request->getVar("multilanguage")['description_short'],
                    'description'   => $this->request->getVar("multilanguage")['description'],
                    'link_rewrite' => $this->request->getVar("multilanguage")['link_rewrite'],
                    'meta_title' => $this->request->getVar("multilanguage")['meta_title'],
                    'meta_keywords' => $this->request->getVar("multilanguage")['meta_keywords'],
                    'meta_description' => $this->request->getVar("multilanguage")['meta_description'],
                ]
        ];

        if($image = $this->request->getFile('image')){
            $validated = $this->validate([
                'image' => [
                    'uploaded[image]',
                    'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[image,4096]',
                ],
            ]);
            if ($validated) {
                $fileName = $image->getRandomName();
                if($image->move('uploads/categories/original/', $fileName))
                    $images=generateImages(
                        $path='./uploads/categories/',
                        $name=$fileName,
                        $sizes=$this->categoryModel::IMAGE_SIZES
                    );
                $params['img']=$fileName;
            }
        }
       
        $this->db->transStart();
            $id=$this->categoryModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata = $this->categoryModel->find($id);
        $this->db->transComplete();
        $this->categoryModel->generateallparents($idata['id']);
        cache()->deleteMatching('cat_*');
        if ($idata) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/category/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->categoryModel->errors();
            return view('admin/category/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->categoryModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->categoryModel->delete($id);
        cache()->deleteMatching('cat_*');
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/category');
    }
    public function edit($id)
    {
        $item = $this->categoryModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->setcache();
        $this->data['data'] = $this->categoryModel->setter($id,$this->data['languages']['codes']);
        /*breadcrumb*/
		$categorienav="Catálogo";
		$parents=explode(',',$this->data['data']['parents']);
		foreach ($parents as $id)
			if(isset($this->data['listcategories'][$id]) && $id!=$this->data['data']['id'])
                $categorienav.=" > ".$this->data['listcategories'][$id]['name'];
        $this->data['categorienav'] = $categorienav;

        

        return view('admin/category/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->categoryModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $params = [
			'id' => $id,
			'status' => $this->request->getVar('status'),
            'parent_id' => $this->request->getVar('parent_id'),
            'position' => $this->request->getVar('position'),
            'created_at' => date('Y-m-d H:i:s'),
            'multilanguage' => 
                [
                    'name'          => $this->request->getVar("multilanguage")['name'],
                    'description_short'   => $this->request->getVar("multilanguage")['description_short'],
                    'description'   => $this->request->getVar("multilanguage")['description'],
                    'link_rewrite' => $this->request->getVar("multilanguage")['link_rewrite'],
                    'meta_title' => $this->request->getVar("multilanguage")['meta_title'],
                    'meta_keywords' => $this->request->getVar("multilanguage")['meta_keywords'],
                    'meta_description' => $this->request->getVar("multilanguage")['meta_description'],
                ]
        ];


        $validated = $this->validate([
            'image' => [
                'uploaded[image]',
                'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[image,4096]',
            ],
        ]);
        if ($validated) {
            $image = $this->request->getFile('image');
            $fileName = $image->getRandomName();
           if($image->move('uploads/categories/original/', $fileName))
                $images=generateImages(
                    $path='./uploads/categories/',
                    $name=$fileName,
                    $sizes=$this->categoryModel::IMAGE_SIZES
                );
            $params['img']=$fileName;
        }
        
        $this->db->transStart();
            $id=$this->categoryModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
        $this->db->transComplete();
        $this->categoryModel->generateallparents($id);
        cache()->deleteMatching('cat_*');
        if ($this->categoryModel->errors()) {
            $this->data['errors'] = $this->categoryModel->errors();
            return view('admin/category/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/category/edit/'.$id);
        }

    }
    public function imgdel()
    {
        $col=$this->request->getVar('col');
        $id=$this->request->getVar('id');
        $item = $this->categoryModel->find($id);
        if (!$item)
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $params = ['id' => $id];
        if($col=='img')$params['img']='';
        if($col=='pimg')$params['pimg']='';
        $this->db->transStart();
        $this->categoryModel->save($params);
        $this->db->transComplete();
        cache()->deleteMatching('cat_*');
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/category');
    }
    
    public function cleancache()
    {
       //$this->generateallslug();
        $this->categoryModel->generateallparents();
        cache()->deleteMatching('cat_*');
        die("Proceso correcto de limpieza cache categorias");exit;
    }
    public function export()
    {
     $categories=$this->categoryModel->get_categories(2,false);
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle('categorias');
        $activeSheet->setCellValue('A1', 'Lista de categorias');
        $activeSheet->setCellValue('A1', 'Fecha :'.date('d/m/Y') );
        $activeSheet->setCellValue('A3', 'ID');
        $activeSheet->setCellValue('B3', 'CATEGORIA');
        $activeSheet->setCellValue('C3', 'CATEGORIA');
        $activeSheet->setCellValue('D3', 'CATEGORIA');
        $activeSheet->setCellValue('E3', 'CATEGORIA');
        $activeSheet->setCellValue('F3', 'CATEGORIA');
        $activeSheet->setCellValue('G3', 'CATEGORIA');
        $activeSheet->setCellValue('H3', 'CATEGORIA');
        $activeSheet->setCellValue('I3', 'CATEGORIA');
        $activeSheet->setCellValue('J3', 'URL');
        $i=3;
        // Add some data
        $l='https://easymerx.com/catalogo/';
        if($categories){
                foreach($categories as $ra){
                        $i++;
                        $activeSheet
                                ->setCellValue('A'.$i, $ra['id'])
                                ->setCellValue('B'.$i, $ra['name'])
                                ->setCellValue('J'.$i, $l.$ra['link_rewrite']);
                        if(isset($ra['sub']) && is_array($ra['sub']))
                        foreach ($ra['sub'] as  $vb) {
                            $i++;
                            $activeSheet
                                ->setCellValue('A'.$i, $vb['id'])
                                ->setCellValue('C'.$i, $vb['name'])
                                ->setCellValue('J'.$i, $l.$vb['link_rewrite']);
                                if(isset($vb['sub']) && is_array($vb['sub']))
                                foreach ($vb['sub'] as  $vc) {
                                    $i++;
                                    $activeSheet
                                        ->setCellValue('A'.$i, $vc['id'])
                                        ->setCellValue('D'.$i, $vc['name'])
                                        ->setCellValue('J'.$i, $l.$vc['link_rewrite']);
                                    if(isset($vc['sub']) && is_array($vc['sub']))
                                    foreach ($vc['sub'] as  $vd) {
                                        $i++;
                                        $activeSheet
                                            ->setCellValue('A'.$i, $vd['id'])
                                            ->setCellValue('E'.$i, $vd['name'])
                                            ->setCellValue('J'.$i, $l.$vd['link_rewrite']);
                                        if(isset($vd['sub']) && is_array($vd['sub']))
                                        foreach ($vd['sub'] as  $ve) {
                                            $i++;
                                            $activeSheet
                                                ->setCellValue('A'.$i, $ve['id'])
                                                ->setCellValue('F'.$i, $ve['name'])
                                                ->setCellValue('J'.$i, $l.$ve['link_rewrite']);
                                            if(isset($ve['sub']) && is_array($ve['sub']))
                                            foreach ($ve['sub'] as  $vf) {
                                                $i++;
                                                $activeSheet
                                                    ->setCellValue('A'.$i, $vf['id'])
                                                    ->setCellValue('G'.$i, $vf['name'])
                                                    ->setCellValue('J'.$i, $l.$vf['link_rewrite']);
                                                if(isset($vf['sub']) && is_array($vf['sub']))
                                                foreach ($vf['sub'] as  $vg) {
                                                    $i++;
                                                    $activeSheet
                                                        ->setCellValue('A'.$i, $vg['id'])
                                                        ->setCellValue('H'.$i, $vg['name'])
                                                        ->setCellValue('J'.$i, $l.$vg['link_rewrite']);
                                                        if(isset($vg['sub']) && is_array($vg['sub']))
                                                        foreach ($vg['sub'] as  $vh) {
                                                            $i++;
                                                            $activeSheet
                                                                ->setCellValue('A'.$i, $vh['id'])
                                                                ->setCellValue('I'.$i, $vh['name'])
                                                                ->setCellValue('J'.$i, $l.$vh['link_rewrite']);
                                                        }
                                                }
                                            }
                                        }
                                    }
                                }
                        }
                }
        }

        // Redirect output to a client's web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ticomusica_categorias_'.date('d-m-Y').'.xlsx"');
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
