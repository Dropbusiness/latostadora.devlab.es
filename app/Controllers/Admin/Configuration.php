<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ConfigurationModel;
use App\Models\ProductModel;
use App\Models\ProductImageModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class Configuration extends BaseController
{
    protected $configurationModel;
    protected $productModel;
    protected $productImageModel;
    public function __construct()
    {
        $this->configurationModel = new ConfigurationModel();
        $this->productModel = new ProductModel();
        $this->productImageModel = new ProductImageModel();
        $this->data['currentAdminMenu'] = 'user-role';
        $this->data['currentAdminSubMenu'] = 'configuration';
    }
    public function add()
    {
        return view('admin/configuration/form', $this->data);
    }
    public function index()
    {
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->configurationModel->getdata($nb_page=30,$page);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        return view('admin/configuration/index', $this->data);
    }

    public function save()
    {
        $params = [
			'name' => $this->request->getVar('name'),
			'value' => $this->request->getVar('value')
        ];
        $this->db->transStart();
        $this->configurationModel->save($params);
        $site = $this->configurationModel->find($this->db->insertID());
        $this->db->transComplete();

        cache()->deleteMatching('configuration_*'); 
        if ($site) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/configuration/edit/' . $site['id']);
        } else {
            $this->data['errors'] = $this->configurationModel->errors();
            return view('admin/configuration/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->configurationModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->configurationModel->delete($id);
        cache()->deleteMatching('configuration_*'); 
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/configuration');
    }

    public function edit($id)
    {
        $this->data['data'] = $site = $this->configurationModel->find($id);
        return view('admin/configuration/form', $this->data);
    }

    public function update($id)
    {

        $item = $this->configurationModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
            'id' => $id,
			'name' => $this->request->getVar('name'),
			'value' => $this->request->getVar('value'),
        ];

        if ($this->validate([
            'image' => [
                'uploaded[image]',
                'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[image,4096]',
            ],
        ]) && strpos($params['name'],'_IMG_')!==false) {
            $image = $this->request->getFile('image');
            $fileName = $image->getRandomName();
           if($image->move('uploads/configuration/', $fileName))
                $params['value']=$fileName;
        }

        $this->db->transStart();
        $this->configurationModel->save($params);
        $this->db->transComplete();
        cache()->deleteMatching('configuration_*'); 
        if ($this->configurationModel->errors()) {
            $this->data['errors'] = $this->configurationModel->errors();
            return view('admin/configuration/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/configuration/edit/'.$id);
        }

    }
    /*****************help*************** */
    public function help()
    {
        
        $this->data['currentAdminSubMenu'] = 'help';
        return view('admin/configuration/help', $this->data);
    }
   
}
