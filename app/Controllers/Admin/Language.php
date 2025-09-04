<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\LanguageModel;
class Language extends BaseController
{
    protected $languageModel;
    public function __construct()
    {
        $this->languageModel = new LanguageModel();
        $this->data['currentAdminMenu'] = 'user-role';
        $this->data['currentAdminSubMenu'] = 'language';
        $this->data['statuses'] = $this->languageModel::getStatuses();
    }
    public function add()
    {
        return view('admin/language/form', $this->data);
    }
    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->languageModel->getdata($nb_page=50,$page,$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/language/index', $this->data);
    }

    public function save()
    {
        $params = [
			'name' => $this->request->getVar('name'),
			'code' => $this->request->getVar('code'),
			'status' => $this->request->getVar('status'),
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
           if($image->move('uploads/languages/', $fileName))
            $params['img']=$fileName;
        }

        $this->db->transStart();
        $this->languageModel->save($params);
        $site = $this->languageModel->find($this->db->insertID());
        $this->db->transComplete();
        if ($site) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/language/edit/' . $site['id']);
        } else {
            $this->data['errors'] = $this->languageModel->errors();
            return view('admin/language/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->languageModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->languageModel->delete($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/language');
    }
    public function edit($id)
    {
        $this->data['data'] = $site = $this->languageModel->find($id);
        return view('admin/language/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->languageModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
            'id' => $id,
			'name' => $this->request->getVar('name'),
            'code' => $this->request->getVar('code'),
			'status' => $this->request->getVar('status'),
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
           if($image->move('uploads/languages/', $fileName))
                $params['img']=$fileName;
        }

        $this->db->transStart();
        $this->languageModel->save($params);
        $this->db->transComplete();
        if ($this->languageModel->errors()) {
            $this->data['errors'] = $this->languageModel->errors();
            return view('admin/language/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/language/edit/'.$id);
        }
    }
    public function change($lang)
    {
        $this->session->set('lang',$lang);
        return redirect()->back();
    }
}
