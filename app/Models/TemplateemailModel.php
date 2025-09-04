<?php namespace App\Models;
use CodeIgniter\Model;
class TemplateemailModel extends Model
{
    protected $table      = 'tbl_template_email';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $table_description = 'tbl_template_email_lang';
	protected $table_descriptionKey = 'templateemail_id';
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];
	
	public  function getdata($nb_page, $page,$lang,$s_name='')
    {
        $this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang])
        ->orderBy($this->table.'.id','DESC');
		
		if($s_name!='')
			$this->like($this->table_description.'.name', $s_name);
		
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
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
	public  function getitem($templateemail_id='',$lang=1)
    {
        $this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang]);
		if($templateemail_id!='')
			$this->where($this->table.'.id', $templateemail_id);
		return $this->first();
    }
}
