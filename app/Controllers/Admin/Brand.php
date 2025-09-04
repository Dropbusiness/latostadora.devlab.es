<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\BrandModel;
class Brand extends BaseController
{
    protected $brandModel;
    public function __construct()
    {
        $this->brandModel = new BrandModel();
        $this->data['currentAdminMenu'] = 'products';
        $this->data['currentAdminSubMenu'] = 'brands';
        $this->data['statuses'] = $this->brandModel::getStatuses();
    }
    public function add()
    {
        $this->data['data'] = $this->brandModel->setter(null,$this->data['languages']['codes']);
        return view('admin/brand/form', $this->data);
    }
    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->brandModel->getdata($nb_page=50,$page,$this->data['lang_id'],$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/brand/index', $this->data);
    }

    public function save()
    {
        $params = [
			'id' => $this->request->getVar('id'),
			'status' => $this->request->getVar('status'),
            'created_at' => date('Y-m-d H:i:s'),
            'multilanguage' => 
                [
                    'name'          => $this->request->getVar("multilanguage")['name'],
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
        if ($validated){
            $image = $this->request->getFile('image');
            $fileName = $image->getRandomName();
            if($image->move('uploads/brands/original/', $fileName))
                $images=generateImages(
                        $path='./uploads/brands/',
                        $name=$fileName,
                        $sizes=$this->brandModel::IMAGE_SIZES
                    );
            $params['img']=$fileName;
        }
        
        $this->db->transStart();
            $id=$this->brandModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata = $this->brandModel->find($id);
            cache()->deleteMatching('brands_*'); 
        $this->db->transComplete();
        if ($id) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/brand/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->brandModel->errors();
            return view('admin/brand/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->brandModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->brandModel->deletedata($id);
        cache()->deleteMatching('brands_*'); 
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/brand');
    }
    public function edit($id)
    {
        $item = $this->brandModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['data'] = $this->brandModel->setter($id,$this->data['languages']['codes']);
        return view('admin/brand/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->brandModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
			'id' => $this->request->getVar('id'),
			'status' => $this->request->getVar('status'),
            'updated_at' => date('Y-m-d H:i:s'),
            'multilanguage' => 
                [
                    'name'          => $this->request->getVar("multilanguage")['name'],
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
           if($image->move('uploads/brands/original/', $fileName))
                $images=generateImages(
                    $path='./uploads/brands/',
                    $name=$fileName,
                    $sizes=$this->brandModel::IMAGE_SIZES
                );
            $params['img']=$fileName;
        }

        $this->db->transStart();
            $id=$this->brandModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->brandModel->find($id);
            cache()->deleteMatching('brands_*'); 
        $this->db->transComplete();
        if ($this->brandModel->errors()) {
            $this->data['errors'] = $this->brandModel->errors();
            return view('admin/brand/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/brand/edit/'.$id);
        }
    }
    
}
