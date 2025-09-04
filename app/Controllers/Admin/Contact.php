<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ContactModel;
class Contact extends BaseController
{
    protected $contactModel;
    public function __construct()
    {
        $this->contactModel = new ContactModel();
        $this->data['currentAdminMenu'] = 'ecommerce';
        $this->data['currentAdminSubMenu'] = 'contact';
        $this->data['statuses'] = $this->contactModel::getStatuses();
        $this->data['ctype']=$this->contactModel::getCtype();
    }

    public function add()
    {
        return view('admin/contact/form', $this->data);
    }

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->contactModel->getdata($nb_page=30,$page,$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        return view('admin/contact/index', $this->data);
    }

    public function show($id)
    {
        $data = $this->contactModel->find($id);
        $this->data['data'] = $data;
        $this->data['files'] = $this->db->table('tbl_contactfile')->where(['contact_id'=>$data['id']])->get()->getResultArray();
        return view('admin/contact/show', $this->data);
    }

}
