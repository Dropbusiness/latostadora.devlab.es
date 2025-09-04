<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\TemplateemailModel;
class Templateemail extends BaseController
{
    protected $templateemailModel;
    public function __construct()
    {
        $this->templateemailModel = new TemplateemailModel();
        $this->data['currentAdminMenu'] = 'user-role';
        $this->data['currentAdminSubMenu'] = 'templateemail';
        $this->data['statuses'] = $this->templateemailModel::getStatuses();
    }
    public function add()
    {
        $this->data['data'] = $this->templateemailModel->setter(null,$this->data['languages']['codes']);
        return view('admin/templateemail/form', $this->data);
    }
    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->templateemailModel->getdata($nb_page=50,$page,$this->data['lang_id'],$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/templateemail/index', $this->data);
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
                    'subject' => $this->request->getVar("multilanguage")['subject'],
                ]
        ];

        
        $this->db->transStart();
            $id=$this->templateemailModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata = $this->templateemailModel->find($id);
        $this->db->transComplete();
        if ($id) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/templateemail/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->templateemailModel->errors();
            return view('admin/templateemail/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->templateemailModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->templateemailModel->deletedata($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/brand');
    }
    public function edit($id)
    {
        $item = $this->templateemailModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['data'] = $this->templateemailModel->setter($id,$this->data['languages']['codes']);
        return view('admin/templateemail/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->templateemailModel->find($id);
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
                    'subject' => $this->request->getVar("multilanguage")['subject'],
                ]
        ];
     

        $this->db->transStart();
            $id=$this->templateemailModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->templateemailModel->find($id);
        $this->db->transComplete();
        if ($this->templateemailModel->errors()) {
            $this->data['errors'] = $this->templateemailModel->errors();
            return view('admin/templateemail/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/templateemail/edit/'.$id);
        }
    }
    
}
