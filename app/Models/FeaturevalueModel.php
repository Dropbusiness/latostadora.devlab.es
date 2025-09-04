<?php namespace App\Models;
use CodeIgniter\Model;
class FeaturevalueModel extends Model
{
    protected $table      = 'tbl_feature_value';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id', 'id_feature','status'];
    protected $table_description = 'tbl_feature_value_lang';
	protected $table_descriptionKey = 'feature_value_id';
	const STATUSES = [
		'1' => 'Activo',
		'2' => 'Desactivo',
	];
	public static function getStatuses()
	{
		return self::STATUSES;
	}
    public function setter($id,$languages){
        $reg=($id>0?$this->find($id):[]);
		$array_cols=$this->db->getFieldNames($this->table);
        $data=array();
        foreach($array_cols as $c) $data[$c]=(isset($reg[$c])?$reg[$c]:'');

        $builder_description = $this->db->table($this->table_description);
		$array_cols=$this->db->getFieldNames($this->table_description);
		foreach ($array_cols as $c) {
			foreach ($languages as $code => $lg) {
				$value='';
				if($data[$this->primaryKey]!=''){
					$item_d=$builder_description->select($c)->where(['id_lang'=>$lg['id'],$this->table_descriptionKey=>$data[$this->primaryKey]])->get()->getRowArray();
					if(!empty($item_d))
						$value=$item_d[$c];
				}
				$data['multilanguage'][$c][$lg['id']]=$value;
			}
		}
		
        return $data;
    }
	public function savedata($data,$languages,$lang)
    {
		$values=array();
        $array_cols=$this->db->getFieldNames($this->table);
        foreach($array_cols as $c){
            if(array_key_exists($c, $data)){
                $values[$c]=$data[$c];
            }
        }
		$builder = $this->db->table($this->table);
        $pk=null;
        if(!empty($data[$this->primaryKey])){
			$pk=$data[$this->primaryKey];
			$builder->set($values);
			$builder->where($this->primaryKey,$data[$this->primaryKey]);
			$builder->update($values);
        }
        else{
            unset($data[$this->primaryKey]);
			$builder->set($values);
			$builder->insert($values);
			$pk = $this->db->insertID();
        }

		if (isset($data['multilanguage'])) {
				$builder_description = $this->db->table($this->table_description);
				$array_cols=$this->db->getFieldNames($this->table_description);
				foreach ($languages as $code => $lg) {
					$values=[];
					foreach ($data['multilanguage'] as $fieldname => $fieldvalues)
						if(in_array($fieldname,$array_cols) && isset($fieldvalues[$lg['id']]))
								$values[$fieldname]=$fieldvalues[$lg['id']];
					if(count($values)){
						$values['id_lang']= $lg['id'];
						$values[$this->table_descriptionKey]= $pk;
						$builder_description->set($values);
						$builder_description->replace($values);
					}
				}
		}
		return $pk;
	}

	public function deletedata(int $id)
	{
			$builder = $this->db->table($this->table);
			$builder->delete([$this->primaryKey => $id]);
			$builder_description = $this->db->table($this->table_description);
			$builder_description->delete([$this->table_descriptionKey => $id]);
	}
	public  function getdata($nb_page, $page,$lang,$s_name='',$id_feature='')
    {
        $this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang])
        ->orderBy($this->table.'.id_feature','DESC');
		
		if($s_name!='')
			$this->like($this->table_description.'.name', $s_name);
		if($id_feature!='')
			$this->where($this->table.'.id_feature', $id_feature);
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
	
    public  function getallfv($isfilter=false)
    {
        $rdata=[];
		if($isfilter){
			$data= $this->query('SELECT * FROM tbl_feature_value where id in (SELECT fp.id_feature_value FROM tbl_feature_product as fp INNER JOIN tbl_product as p ON p.id=fp.id_product and p.ptipo=4 group by fp.id_feature_value) order by name ASC')->getResult('array');
		}else{
			$data= $this->query('SELECT * FROM tbl_feature_value order by name ASC')->getResult('array');
		}
        
		foreach ($data as $k => $v) 
            $rdata[$v['id_feature']][$v['id']]=strip_tags($v['name']);
        return $rdata;
    }
}
