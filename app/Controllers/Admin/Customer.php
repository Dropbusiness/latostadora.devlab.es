<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\CustomerModel;
class Customer extends BaseController
{
    protected $customerModel;
    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->data['currentAdminMenu'] = 'ecommerce';
        $this->data['currentAdminSubMenu'] = 'customer';
        $this->data['statuses'] = $this->customerModel::getStatuses();
    }

    public function add()
    {
        return view('admin/customer/form', $this->data);
    }

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->customerModel->getdata($nb_page=30,$page,$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        return view('admin/customer/index', $this->data);
    }

    public function save()
    {
        $params = [
			'company' => $this->request->getVar('company'),
			'cif' => $this->request->getVar('cif'),
            'country' => $this->request->getVar('country'),
            'city' => $this->request->getVar('city'),
            'address' => $this->request->getVar('address'),
            'firstname' => $this->request->getVar('firstname'),
            'lastname' => $this->request->getVar('lastname'),
            'email' => $this->request->getVar('email'),
            'passwd' => md5($this->request->getVar('passwd')),
            'password_token' => md5(uniqid()),
			'status' => $this->request->getVar('status'),
            'optin' => $this->request->getVar('optin'),
            

        ];
        $this->db->transStart();
        $this->customerModel->save($params);
        $site = $this->customerModel->find($this->db->insertID());
        $this->db->transComplete();

        if ($site) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/customer/edit/' . $site['id']);
        } else {
            $this->data['errors'] = $this->customerModel->errors();
            return view('admin/customer/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->customerModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->customerModel->delete($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/customer');
    }

    public function edit($id)
    {
        $this->data['data'] = $site = $this->customerModel->find($id);
        return view('admin/customer/form', $this->data);
    }

    public function update($id)
    {

        $item = $this->customerModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
            'id' => $id,
			'company' => $this->request->getVar('company'),
			'cif' => $this->request->getVar('cif'),
            'country' => $this->request->getVar('country'),
            'city' => $this->request->getVar('city'),
            'address' => $this->request->getVar('address'),
            'firstname' => $this->request->getVar('firstname'),
            'lastname' => $this->request->getVar('lastname'),
            'email' => $this->request->getVar('email'),
			'status' => $this->request->getVar('status'),
            'optin' => $this->request->getVar('optin'),
            'password_token' => md5(uniqid()),
        ];
        $passwd=$this->request->getVar('passwd');
        if($passwd!='')
            $params['passwd']=md5($passwd);
        $this->db->transStart();
        $this->customerModel->save($params);
        $this->db->transComplete();
        if ($this->customerModel->errors()) {
            $this->data['errors'] = $this->customerModel->errors();
            return view('admin/customer/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/customer/edit/'.$id);
        }

    }

}
