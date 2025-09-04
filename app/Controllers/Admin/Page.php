<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\PageModel;
class Page extends BaseController
{
    protected $pageModel;
    public function __construct()
    {
        $this->pageModel = new PageModel();
        $this->data['currentAdminMenu'] = 'contents';
        $this->data['currentAdminSubMenu'] = 'pages';
        $this->data['statuses'] = $this->pageModel::getStatuses();
        $this->data['groups'] = $this->pageModel::getGroups();
    }
    public function add()
    {
        $this->data['data'] = $this->pageModel->setter(null,$this->data['languages']['codes']);
        return view('admin/page/form', $this->data);
    }
    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->pageModel->getdata($nb_page=50,$page,$this->data['lang_id'],$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/page/index', $this->data);
    }

    public function save()
    {
        $params = [
			'id' => $this->request->getVar('id'),
			'status' => $this->request->getVar('status'),
            'created_at' => date('Y-m-d H:i:s'),
            'group_id' => $this->request->getVar('group_id'),
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
       
        $this->db->transStart();
            $id=$this->pageModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata = $this->pageModel->find($id);
            cache()->deleteMatching('allpage_*'); 
        $this->db->transComplete();
        if ($id) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/page/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->pageModel->errors();
            return view('admin/page/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->pageModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->pageModel->deletedata($id);
        cache()->deleteMatching('allpage_*'); 
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/page');
    }
    public function edit($id)
    {
        $item = $this->pageModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['data'] = $this->pageModel->setter($id,$this->data['languages']['codes']);
        return view('admin/page/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->pageModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
			'id' => $this->request->getVar('id'),
			'status' => $this->request->getVar('status'),
            'updated_at' => date('Y-m-d H:i:s'),
            'group_id' => $this->request->getVar('group_id'),
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
       
        $this->db->transStart();
            $id=$this->pageModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->pageModel->find($id);
            cache()->deleteMatching('allpage_*'); 
        $this->db->transComplete();
        if ($this->pageModel->errors()) {
            $this->data['errors'] = $this->pageModel->errors();
            return view('admin/page/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/page/edit/'.$id);
        }
    }
    
}
