<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\EventsModel;
use App\Models\ArtistsModel;
use App\Models\ToursModel;
class Events extends BaseController
{
    protected $eventsModel;
    protected $artistsModel;
    protected $toursModel;
    public function __construct()
    {
        $this->eventsModel = new EventsModel();
        $this->artistsModel = new ArtistsModel();
        $this->toursModel = new ToursModel();
        $this->data['currentAdminMenu'] = 'events';
        $this->data['currentAdminSubMenu'] = 'event';
        $this->data['statuses'] = $this->eventsModel::getStatuses();

    }

    public function add()
    {
        $this->data['artists'] =  $this->artistsModel->list();
        $this->data['tours'] =  $this->toursModel->list();
        return view('admin/event/form', $this->data);
        
    }

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $this->data['s_artist']=$this->request->getVar('s_artist');
        $this->data['s_tour']=$this->request->getVar('s_tour');
        $this->data['s_status']=$this->request->getVar('s_status');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->eventsModel->getdata($nb_page=30,$page,$this->data['s_name'],$this->data['s_artist'],$this->data['s_tour'],$this->data['s_status']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['artists'] =  $this->artistsModel->list();
        $this->data['tours'] =  $this->toursModel->list();
        return view('admin/event/index', $this->data);
    }

    public function save()
    {
        $tour_id=$this->request->getVar('tour_id');
        $tour=$this->toursModel->where('id',$tour_id)->first();
        $city=$this->request->getVar('city');
        $params = [
            'artists_id' => $tour['artists_id'],
			'tour_id' => $tour['id'],
			'city' => $city,
			'slug' => slugify($city),
            'date' => $this->request->getVar('date'),
			'status' => $this->request->getVar('status'),       
        ];
        $this->db->transStart();
        $this->eventsModel->save($params);
        $site = $this->eventsModel->find($this->db->insertID());
        $this->db->transComplete();

        if ($site) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/event/edit/' . $site['id']);
        } else {
            $this->data['errors'] = $this->eventsModel->errors();
            return view('admin/event/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->eventsModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->eventsModel->delete($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/event');
    }

    public function edit($id)
    {
        $this->data['data'] = $site = $this->eventsModel->find($id);
        $this->data['artists'] =  $this->artistsModel->list();
        $this->data['tours'] =  $this->toursModel->list();
        return view('admin/event/form', $this->data);
    }

    public function update($id)
    {

        $item = $this->eventsModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tour_id=$this->request->getVar('tour_id');
        $tour=$this->toursModel->where('id',$tour_id)->first();
        $city=$this->request->getVar('city');
        $params = [
            'id' => $id,
			'artists_id' => $tour['artists_id'],
			'tour_id' => $tour['id'],
            'city' => $city,
			'slug' => slugify($city),
            'date' => $this->request->getVar('date'),
			'status' => $this->request->getVar('status'),  

        ];
        $this->db->transStart();
        $this->eventsModel->save($params);
        $this->db->transComplete();
        if ($this->eventsModel->errors()) {
            $this->data['errors'] = $this->eventsModel->errors();
            return view('admin/event/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/event/edit/'.$id);
        }

    }

}
