<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ArtistsModel;
class Artists extends BaseController
{
    protected $artistsModel;
    public function __construct()
    {
        $this->artistsModel = new ArtistsModel();
        $this->data['currentAdminMenu'] = 'events';
        $this->data['currentAdminSubMenu'] = 'artists';
        $this->data['statuses'] = $this->artistsModel::getStatuses();
    }

    public function add()
    {
        return view('admin/artists/form', $this->data);
    }

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->artistsModel->getdata($nb_page=30,$page,$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        return view('admin/artists/index', $this->data);
    }

    public function save()
    {
        $name=$this->request->getVar('name');
        $params = [
			'name' => $this->request->getVar('name'),
			'slug' => slugify($name),
			'status' => $this->request->getVar('status'),
            

        ];
        $this->db->transStart();
        $this->artistsModel->save($params);
        $site = $this->artistsModel->find($this->db->insertID());
        $this->db->transComplete();

        if ($site) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/artists/edit/' . $site['id']);
        } else {
            $this->data['errors'] = $this->artistsModel->errors();
            return view('admin/artists/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->artistsModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->artistsModel->delete($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/artists');
    }

    public function edit($id)
    {
        $this->data['data'] = $site = $this->artistsModel->find($id);
        return view('admin/artists/form', $this->data);
    }

    public function update($id)
    {

        $item = $this->artistsModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $name=$this->request->getVar('name');
        $params = [
            'id' => $id,
			'name' => $name,
			'slug' => slugify($name),
			'status' => $this->request->getVar('status'),

        ];
        $this->db->transStart();
        $this->artistsModel->save($params);
        $this->db->transComplete();
        if ($this->artistsModel->errors()) {
            $this->data['errors'] = $this->artistsModel->errors();
            return view('admin/artists/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/artists/edit/'.$id);
        }

    }

}
