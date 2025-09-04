<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\FeatureModel;
class Feature extends BaseController
{
    protected $featureModel;
    public function __construct()
    {
        $this->featureModel = new FeatureModel();
        $this->data['currentAdminMenu'] = 'products';
        $this->data['currentAdminSubMenu'] = 'feature';
        $this->data['statuses'] = $this->featureModel::getStatuses();
    }

    public function add()
    {
        $this->data['data'] = $this->featureModel->setter(null,$this->data['languages']['codes']);
        return view('admin/feature/form', $this->data);
    }

    public function index()
    {
        $this->data['s_name']=$this->request->getVar('s_name');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $alldata=$this->featureModel->getdata($nb_page=30,$page,$this->data['lang_id'],$this->data['s_name']);
        $this->data['alldata'] = $alldata['data'];
        $this->data['pager'] = $alldata['pager']->links('default','bootstrap_pagination');
        $this->data['total'] = $alldata['pager']->getTotal('default');
        return view('admin/feature/index', $this->data);
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
            $id=$this->featureModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->featureModel->find($id);
        $this->db->transComplete();


        if ($idata) {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/feature/edit/' . $idata['id']);
        } else {
            $this->data['errors'] = $this->featureModel->errors();
            return view('admin/feature/add', $this->data);
        }
    }
    public function delete($id)
    {
        $item = $this->featureModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->featureModel->deletedata($id);
        $this->session->setFlashdata('success', 'Data has been deleted.');
        return redirect()->to('/admin/feature');
    }

    public function edit($id)
    {
        $item = $this->featureModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->data['data'] = $this->featureModel->setter($id,$this->data['languages']['codes']);

        return view('admin/feature/form', $this->data);
    }

    public function update($id)
    {

        $item = $this->featureModel->find($id);
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
            $id=$this->featureModel->savedata($params,$this->data['languages']['codes'],$this->data['lang']);
            $idata=$this->featureModel->find($id);
        $this->db->transComplete();

        if ($this->featureModel->errors()) {
            $this->data['errors'] = $this->featureModel->errors();
            return view('admin/feature/form', $this->data);
        } else {
            $this->session->setFlashdata('success', 'Data has been saved.');
            return redirect()->to('/admin/feature/edit/'.$id);
        }

    }
    public function export()
	{
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);
        $datalanguages=[];
		foreach ($this->db->query("SELECT * FROM `tbl_feature_lang`")->getResultArray() as $row)
            $datalanguages[$row['feature_id']][$row['id_lang']]=$row['name'];

        $alldata = $this->featureModel->find();
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
                    #languages
                    foreach ($this->data['languages']['ids'] as $lang => $language) {
                        $ih++;
                        $item[$ih]= isset($datalanguages[$row['id']][$lang])?$datalanguages[$row['id']][$lang]:'';
                        $header[$ih]=$language['name'];
                    }
                    $items[] = $item;
            }
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="features_' . date('d-m-Y') . '.csv"');
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
