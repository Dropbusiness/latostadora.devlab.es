<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\AttributesModel;
use App\Models\AttributesvalueModel;
class Attributesvalue extends BaseController
{
    protected $attributesModel;
    protected $attributesvalueModel;
    public function __construct()
    {
        $this->attributesModel = new AttributesModel();
        $this->attributesvalueModel = new AttributesvalueModel();
        $this->data['currentAdminMenu'] = 'products';
        $this->data['currentAdminSubMenu'] = 'attributesvalue';
        $this->data['statuses'] = $this->attributesvalueModel::getStatuses();
    }
   
    public function add()
    {
        $this->data['attributes'] = $this->attributesModel->getlist($this->data['lang_id']);
        $this->data['data'] = $this->attributesModel->setter(null,$this->data['languages']['codes']);
        return view('admin/attributesvalue/form', $this->data);
    }

    public function index()
    {
        $this->data['attributes'] = $this->attributesModel->getlist($this->data['lang_id']);
        $this->data['s_name']=$this->request->getVar('s_name');
        $this->data['s_attributes']=$this->request->getVar('s_attributes');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->attributesvalueModel->getdata($nb_page=30,$page,$this->data['lang_id'],$this->data['s_name'],$this->data['s_attributes']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/attributesvalue/index', $this->data);
    }

    public function save()
    {
        $params = [
			'attribute_id' => $this->request->getVar('attribute_id'),
            'status' => $this->request->getVar('status'),
            'code' => $this->request->getVar('code'),
            'position' => $this->request->getVar('position'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                ]
        ];
      
        $this->db->transStart();
            $id=$this->attributesvalueModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->attributesvalueModel->find($id);
        $this->db->transComplete();

        if ($idata) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/attributesvalue/edit/' . $idata['id']);
        } else {
            $this->data['attributes'] = $this->attributesModel->getlist($this->data['lang_id']);
            $this->data['data'] = $this->attributesModel->setter(null,$this->data['languages']['codes']);
            $this->data['errors'] = $this->attributesvalueModel->errors();
            return view('admin/attributesvalue/form', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->attributesvalueModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->attributesvalueModel->delete($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/attributesvalue');
    }

    public function edit($id)
    {
        $item = $this->attributesvalueModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['attributes'] = $this->attributesModel->getlist($this->data['lang_id']);
        $this->data['data'] = $this->attributesvalueModel->setter($id,$this->data['languages']['codes']);
        return view('admin/attributesvalue/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->attributesvalueModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
			'id' => $id,
			'attribute_id' => $this->request->getVar('attribute_id'),
            'status' => $this->request->getVar('status'),
            'code' => $this->request->getVar('code'),
            'position' => $this->request->getVar('position'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                ]
        ];
      
        $this->db->transStart();
            $id=$this->attributesvalueModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->attributesvalueModel->find($id);
        $this->db->transComplete();

        if ($this->attributesvalueModel->errors()) {
            $this->data['errors'] = $this->attributesvalueModel->errors();
            return view('admin/attributesvalue/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/attributesvalue/edit/'.$id);
        }

    }
    public function export()
	{
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);

        $this->data['attributes'] = $this->attributesModel->getlist($this->data['lang_id']);

        $datalanguages=[];
		foreach ($this->db->query("SELECT * FROM `tbl_attributes_value_lang`")->getResultArray() as $row)
            $datalanguages[$row['attribute_value_id']][$row['id_lang']]=$row['name'];

        $alldata = $this->attributesvalueModel->find();
        $header=$items=$item=[];
        if($alldata){
            foreach($alldata as $row){
                    $ih=0;
                    #id
                    $item[$ih]= $row['id'];
                    $header[$ih]='id';
                    #name
                    $ih++;
                    $item[$ih] = isset($this->data['attributes'][$row['attribute_id']])?$this->data['attributes'][$row['attribute_id']]:'';
                    $header[$ih]='grupo atributos';
                    #languages
                    foreach ($this->data['languages']['ids'] as $lang => $language) {
                        $ih++;
                        $item[$ih]= isset($datalanguages[$row['id']][$lang])?$datalanguages[$row['id']][$lang]:'';
                        $header[$ih]=$language['name'];
                    }
                    $items[] = $item;
            }
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="attributes_values_' . date('d-m-Y') . '.csv"');
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
