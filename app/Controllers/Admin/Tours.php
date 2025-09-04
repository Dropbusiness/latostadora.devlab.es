<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ToursModel;
use App\Models\ArtistsModel;
class Tours extends BaseController
{
    protected $toursModel;
    protected $artistsModel;
    public function __construct()
    {
        $this->toursModel = new ToursModel();
        $this->artistsModel = new ArtistsModel();
        $this->data['currentAdminMenu'] = 'events';
        $this->data['currentAdminSubMenu'] = 'tours';
        $this->data['statuses'] = $this->toursModel::getStatuses();
    }
    public function add()
{
    $this->data['data'] = $this->toursModel->setter(null, $this->data['languages']['codes']);
    $this->data['artists'] =  $this->artistsModel->list();
    
    return view('admin/tours/form', $this->data);
}

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->toursModel->getdata($nb_page=50,$page,$this->data['lang_id'],$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
     
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        $this->data['artists'] =  $this->artistsModel->list();
        return view('admin/tours/index', $this->data);
    }

    public function save()
    {
        $name=$this->request->getVar("multilanguage")['name'];
        $params = [
			'id' => $this->request->getVar('id'),
            'artists_id' => $this->request->getVar('artists_id'),
            'slug' => slugify($name[1]),
			'status' => $this->request->getVar('status'),
            'custom_email' => $this->request->getVar('custom_email'),
            'custom_contact' => $this->request->getVar('custom_contact'),
            'custom_sendemail' => $this->request->getVar('custom_sendemail'),
            'multilanguage' => 
                [
                    'name'          => $name,
                    'description'   => $this->request->getVar("multilanguage")['description'],
                    'meta_title'    => $this->request->getVar("multilanguage")['meta_title'],
                    'meta_description' => $this->request->getVar("multilanguage")['meta_description'],
                ]
        ];
       ##########################carga imagenes########################
       $errors = [];
       $imagea = $this->request->getFile('image');
       $imageb = $this->request->getFile('image2');
       if ($imagea->isValid()) {
           $validationRules = [
               'uploaded[image]',
               'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]',
               'max_size[image,4096]',
           ];
           if (!$this->validate(['imagea' => $validationRules])) {
               $errors = array_merge($errors, $this->validator->getErrors());
           }else{
               $fileName = $imagea->getRandomName();
               if($imagea->move('uploads/tours/original/', $fileName))
                   $images=generateImages(
                       $path='./uploads/tours/',
                       $name=$fileName,
                       $sizes=$this->toursModel::IMAGE_SIZES
                   );
               $params['img']=$fileName;
           }
       }

       if ($imageb->isValid()) {
           $validationRules = [
               'uploaded[image2]',
               'mime_in[image2,image/jpg,image/jpeg,image/gif,image/png]',
               'max_size[image2,4096]',
           ];
           if (!$this->validate(['imageb' => $validationRules])) {
               $errors = array_merge($errors, $this->validator->getErrors());
           }else{
               $fileName = $imageb->getRandomName();
           if($imageb->move('uploads/tours/original/', $fileName))
                   $images=generateImages(
                       $path='./uploads/tours/',
                       $name=$fileName,
                       $sizes=$this->toursModel::IMAGE_SIZES
                   );
               $params['img_e']=$fileName;
           }
       }

       if (!empty($errors)) {
           return redirect()->back()->withInput()->with('errors', $errors);
       }
       ########################## ///carga imagenes########################

        $this->db->transStart();
        $id=$this->toursModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
        $idata=$this->toursModel->find($id);
        $this->db->transComplete();

        if ($idata) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/tours/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->toursModel->errors();
            return view('admin/tours/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->toursModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->toursModel->deletedata($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/tours');
    }
    public function edit($id)
    {
        $item = $this->toursModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['data'] = $this->toursModel->setter($id,$this->data['languages']['codes']);
        $this->data['artists'] =  $this->artistsModel->list();
        return view('admin/tours/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->toursModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $name=$this->request->getVar("multilanguage")['name'];
        $params = [
			'id' => $this->request->getVar('id'),
            'artists_id' => $this->request->getVar('artists_id'),
            'slug' => slugify($name[1]),
			'status' => $this->request->getVar('status'),
            'custom_email' => $this->request->getVar('custom_email'),
            'custom_contact' => $this->request->getVar('custom_contact'),
            'custom_sendemail' => $this->request->getVar('custom_sendemail'),
            'multilanguage' => 
                [
                    'name'          => $name,
                    'description'   => $this->request->getVar("multilanguage")['description'],
                    'meta_title'    => $this->request->getVar("multilanguage")['meta_title'],
                    'meta_description' => $this->request->getVar("multilanguage")['meta_description'],
                ]
        ];
        ##########################carga imagenes########################
        $errors = [];
        $imagea = $this->request->getFile('image');
        $imageb = $this->request->getFile('image2');
        if ($imagea->isValid()) {
            $validationRules = [
                'uploaded[image]',
                'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[image,4096]',
            ];
            if (!$this->validate(['imagea' => $validationRules])) {
                $errors = array_merge($errors, $this->validator->getErrors());
            }else{
                $fileName = $imagea->getRandomName();
                if($imagea->move('uploads/tours/original/', $fileName))
                    $images=generateImages(
                        $path='./uploads/tours/',
                        $name=$fileName,
                        $sizes=$this->toursModel::IMAGE_SIZES
                    );
                $params['img']=$fileName;
            }
        }

        if ($imageb->isValid()) {
            $validationRules = [
                'uploaded[image2]',
                'mime_in[image2,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[image2,4096]',
            ];
            if (!$this->validate(['imageb' => $validationRules])) {
                $errors = array_merge($errors, $this->validator->getErrors());
            }else{
                $fileName = $imageb->getRandomName();
            if($imageb->move('uploads/tours/original/', $fileName))
                    $images=generateImages(
                        $path='./uploads/tours/',
                        $name=$fileName,
                        $sizes=$this->toursModel::IMAGE_SIZES
                    );
                $params['img_e']=$fileName;
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }
        ########################## ///carga imagenes########################

        $this->db->transStart();
            $id=$this->toursModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->toursModel->find($id);
        $this->db->transComplete();

        if ($this->toursModel->errors()) {
            $this->data['errors'] = $this->toursModel->errors();
            return view('admin/tours/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/tours/edit/'.$id);
        }
    }
    
}
