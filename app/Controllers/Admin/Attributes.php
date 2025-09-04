<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\AttributesModel;
class Attributes extends BaseController
{
    protected $attributesModel;
    public function __construct()
    {
        $this->attributesModel = new AttributesModel();
        $this->data['currentAdminMenu'] = 'products';
        $this->data['currentAdminSubMenu'] = 'attributes';
        $this->data['statuses'] = $this->attributesModel::getStatuses();
    }

    public function add()
    {
        $this->data['data'] = $this->attributesModel->setter(null,$this->data['languages']['codes']);
        return view('admin/attributes/form', $this->data);
    }

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->attributesModel->getdata($nb_page=30,$page,$this->data['lang_id'],$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/attributes/index', $this->data);
    }

    public function save()
    {

        $params = [
			'position' => $this->request->getVar('position'),
            'status' => $this->request->getVar('status'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                ]
        ];
      
        $this->db->transStart();
            $id=$this->attributesModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->attributesModel->find($id);
        $this->db->transComplete();


        if ($idata) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/attributes/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->attributesModel->errors();
            return view('admin/attributes/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->attributesModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->attributesModel->deletedata($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/attributes');
    }

    public function edit($id)
    {
        $item = $this->attributesModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['data'] = $this->attributesModel->setter($id,$this->data['languages']['codes']);

        return view('admin/attributes/form', $this->data);
    }

    public function update($id)
    {

        $item = $this->attributesModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
			'id' => $id,
			'position' => $this->request->getVar('position'),
            'status' => $this->request->getVar('status'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                ]
        ];
      
        $this->db->transStart();
            $id=$this->attributesModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->attributesModel->find($id);
        $this->db->transComplete();

        if ($this->attributesModel->errors()) {
            $this->data['errors'] = $this->attributesModel->errors();
            return view('admin/attributes/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/attributes/edit/'.$id);
        }

    }
    public function export()
	{
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);
        $datalanguages=[];
		foreach ($this->db->query("SELECT * FROM `tbl_attributes_lang`")->getResultArray() as $row)
            $datalanguages[$row['attribute_id']][$row['id_lang']]=$row['name'];

        $alldata = $this->attributesModel->find();
        $header=$items=$item=[];
        if($alldata){
            foreach($alldata as $row){
                    $ih=0;
                    #id
                    $item[$ih]= $row['id'];
                    $header[$ih]='id';
                    #languages
                    foreach ($this->data['languages']['ids'] as $lang => $language) {
                        $ih++;
                        $item[$ih]= isset($datalanguages[$row['id']][$lang])?$datalanguages[$row['id']][$lang]:'';
                        $header[$ih]=$language['name'];
                    }
                    $items[] = $item;
            }
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="attribute_' . date('d-m-Y') . '.csv"');
            header('Cache-Control: max-age=0');
            
            $output = fopen('php://output', 'w');   
            fputcsv($output, $header);
            foreach ($items as $key => $item) {
                fputcsv($output, $item);
            }
            fclose($output);
        }
        exit;
    }
    
}
