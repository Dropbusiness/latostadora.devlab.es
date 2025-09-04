<?php namespace App\Models;
use CodeIgniter\Model;
class PageModel extends Model
{
    protected $table      = 'tbl_page';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id', 'status', 'created_at', 'updated_at','group_id'];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $table_description = 'tbl_page_lang';
	protected $table_descriptionKey = 'page_id';
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];
	const GROUPS = [
		'1' => 'General',
		'2' => 'Guia de tallas',
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
	public static function getGroups()
	{
		return self::GROUPS;
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
	public  function getitem($page_id='',$link_rewrite='',$lang=1)
    {
        $this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang]);
		if($page_id!='')
			$this->where($this->table.'.id', $page_id);
		if($link_rewrite!='')
			$this->where($this->table_description.'.link_rewrite', $link_rewrite);
		return $this->first();
    }
	public  function getLinkrewrites($link_rewrite)
    {
		$data=[];
		$item=$this->db->table($this->table_description)->select($this->table_description.".".$this->table_descriptionKey.' as id')->where($this->table_description.'.link_rewrite', $link_rewrite)->get()->getRowArray();
		if(isset($item['id'])){
			$items=$this->db->table($this->table_description)->select($this->table_description.".link_rewrite,".$this->table_description.".id_lang")->where($this->table_description.'.'.$this->table_descriptionKey, $item['id'])->get()->getResultArray();
			foreach ($items as $k => $row)
					$data[$row['id_lang']]=$row['link_rewrite'];
		}
		return $data;
    }
	public  function listpages($params=[])
    {
		$conditions='';
		if(isset($params['group_id'])){
			$conditions.=' and p.group_id='.$params['group_id'];
		}
		
        $data=[''=>'-'];
       foreach ($this->query('SELECT p.id,pl.name FROM `tbl_page_lang` as pl 
		inner join tbl_page as p on p.id=pl.page_id where pl.id_lang=1 '.$conditions)->getResult('array') as $row)
            $data[$row['id']] = $row['name'];
        return $data;
    }
	public  function getallpage($id_lang=1)
    {
        $data=[];
		foreach ($this->query('SELECT pl.page_id,pl.name,pl.link_rewrite,pl.description FROM tbl_page_lang as pl 
		inner join tbl_page as p on p.id=pl.page_id where p.status=1 and id_lang='.$id_lang)->getResult('array') as $row)
            $data[$row['page_id']] = $row;
        return $data;
    }
}
