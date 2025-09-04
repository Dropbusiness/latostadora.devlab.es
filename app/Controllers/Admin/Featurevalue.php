<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\FeatureModel;
use App\Models\FeaturevalueModel;
class Featurevalue extends BaseController
{
    protected $featureModel;
    protected $featurevalueModel;
    public function __construct()
    {
        $this->featureModel = new FeatureModel();
        $this->featurevalueModel = new FeaturevalueModel();
        $this->data['currentAdminMenu'] = 'products';
        $this->data['currentAdminSubMenu'] = 'featurevalue';
        
        $this->data['statuses'] = $this->featurevalueModel::getStatuses();
        
    }
   
    public function add()
    {
        $this->data['data'] = $this->featureModel->setter(null,$this->data['languages']['codes']);
        $this->data['features'] = $this->featureModel->getlist($this->data['lang_id']);
        return view('admin/featurevalue/form', $this->data);
    }

    public function index()
    {
        $this->data['features'] = $this->featureModel->getlist($this->data['lang_id']);
        $this->data['s_name']=$this->request->getVar('s_name');
        $this->data['s_feature']=$this->request->getVar('s_feature');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->featurevalueModel->getdata($nb_page=30,$page,$this->data['lang_id'],$this->data['s_name'],$this->data['s_feature']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/featurevalue/index', $this->data);
    }

    public function save()
    {
        $params = [
			'name' => $this->request->getVar('name'),
			'id_feature' => $this->request->getVar('id_feature'),
            'status' => $this->request->getVar('status'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                ]
        ];
      
        $this->db->transStart();
            $id=$this->featurevalueModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->featurevalueModel->find($id);
        $this->db->transComplete();

        if ($idata) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/featurevalue/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->featurevalueModel->errors();
            return view('admin/featurevalue/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->featurevalueModel->withDeleted()->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->featurevalueModel->delete($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/featurevalue');
    }

    public function edit($id)
    {
        $item = $this->featurevalueModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['features'] = $this->featureModel->getlist($this->data['lang_id']);
        $this->data['data'] = $this->featurevalueModel->setter($id,$this->data['languages']['codes']);
        return view('admin/featurevalue/form', $this->data);
    }

    public function update($id)
    {
        $item = $this->featurevalueModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $params = [
			'id' => $id,
			'name' => $this->request->getVar('name'),
			'id_feature' => $this->request->getVar('id_feature'),
            'status' => $this->request->getVar('status'),
            'multilanguage' => 
                [
                    'name' => $this->request->getVar("multilanguage")['name'],
                ]
        ];
      
        $this->db->transStart();
            $id=$this->featurevalueModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->featurevalueModel->find($id);
        $this->db->transComplete();

        if ($this->featurevalueModel->errors()) {
            $this->data['errors'] = $this->featurevalueModel->errors();
            return view('admin/featurevalue/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/featurevalue/edit/'.$id);
        }

    }
    public function export()
	{
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);
        $this->data['features'] = $this->featureModel->getlist($this->data['lang_id']);
        $datalanguages=[];
		foreach ($this->db->query("SELECT * FROM `tbl_feature_value_lang`")->getResultArray() as $row)
            $datalanguages[$row['feature_value_id']][$row['id_lang']]=$row['name'];

        $alldata = $this->featurevalueModel->find();
        $header=$items=$item=[];
        if($alldata){
            foreach($alldata as $row){
                    $ih=0;
                    #id
                    $item[$ih]= $row['id'];
                    $header[$ih]='id';
                    #name
                    $ih++;
                    $item[$ih] = $row['name'];
                    $header[$ih]='Nombre referencia';
                    #name
                    $ih++;
                    $item[$ih] = isset($this->data['features'][$row['id_feature']])?$this->data['features'][$row['id_feature']]:'';
                    $header[$ih]='grupo característica';
                    #languages
                    foreach ($this->data['languages']['ids'] as $lang => $language) {
                        $ih++;
                        $item[$ih]= isset($datalanguages[$row['id']][$lang])?$datalanguages[$row['id']][$lang]:'';
                        $header[$ih]=$language['name'];
                    }
                    $items[] = $item;
            }
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="features_values_' . date('d-m-Y') . '.csv"');
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
