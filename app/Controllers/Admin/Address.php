<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\AddressModel;
class Address extends BaseController
{
    protected $addressModel;
    public function __construct()
    {
        $this->addressModel = new AddressModel();
        $this->data['currentAdminMenu'] = 'ecommerce';
        $this->data['currentAdminSubMenu'] = 'address';
        $this->data['statuses'] = $this->addressModel::getStatuses();
    }

    public function add()
    {
        return view('admin/address/form', $this->data);
    }

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->addressModel->getdata($nb_page=30,$page,$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        return view('admin/address/index', $this->data);
    }

    public function save()
    {
        $params = [
			'id_customer' => $this->request->getVar('id_customer'),
			'name' => $this->request->getVar('name'),
            'address' => $this->request->getVar('address'),
            'postcode' => $this->request->getVar('postcode'),
            'city' => $this->request->getVar('city'),
            'state' => $this->request->getVar('state'),
            'phone' => $this->request->getVar('phone'),
            'erp_addressID' => $this->request->getVar('erp_addressID'),
			'status' => $this->request->getVar('status'),
        ];
        $this->db->transStart();
        $this->addressModel->save($params);
        $site = $this->addressModel->find($this->db->insertID());
        $this->db->transComplete();

        if ($site) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/address/edit/' . $site['id']);
        } else {
            $this->data['errors'] = $this->addressModel->errors();
            return view('admin/address/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->addressModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->addressModel->delete($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/address');
    }

    public function edit($id)
    {
        $this->data['data'] = $site = $this->addressModel->find($id);
        return view('admin/address/form', $this->data);
    }

    public function update($id)
    {

        $item = $this->addressModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
            'id' => $id,
			'id_customer' => $this->request->getVar('id_customer'),
			'name' => $this->request->getVar('name'),
            'address' => $this->request->getVar('address'),
            'postcode' => $this->request->getVar('postcode'),
            'city' => $this->request->getVar('city'),
            'state' => $this->request->getVar('state'),
            'phone' => $this->request->getVar('phone'),
            'erp_addressID' => $this->request->getVar('erp_addressID'),
			'status' => $this->request->getVar('status'),
        ];
        $this->db->transStart();
        $this->addressModel->save($params);
        $this->db->transComplete();
        if ($this->addressModel->errors()) {
            $this->data['errors'] = $this->addressModel->errors();
            return view('admin/address/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/address/edit/'.$id);
        }

    }

}
