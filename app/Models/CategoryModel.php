<?php namespace App\Models;
use CodeIgniter\Model;
class CategoryModel extends Model
{
    protected $table      = 'tbl_category';
	protected $primaryKey = 'id';
	protected $allowedFields = [
        'id', 
        'parent_id', 
        'status', 
        'created_at', 
        'updated_at', 
        'img',
        'position',
        'parents',
        'erp_id',
    ];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';

    protected $table_description = 'tbl_category_lang';
	protected $table_descriptionKey = 'category_id';
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];
	const IMAGE_SIZES = [
		/*'large' => [
			'width' => 800,
			'height' => 1024,
		],*/
		'medium' => [
			'width' => 300,
			'height' => 300,
		],
		'small' => [
			'width' => 80,
			'height' => 80,
		],
	];
    protected $tmp = [];
	
    public  function getdata($nb_page, $page,$lang,$parent_id='',$s_name='')
    {
        $this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*,(SELECT count(*) FROM tbl_category as c WHERE c.parent_id=tbl_category.id) AS subcategories")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang])
        ->orderBy($this->table.'.id','DESC');
		if($parent_id!='')
			$this->where($this->table.'.parent_id', $parent_id);

		if($s_name!='')
			$this->like($this->table_description.'.name', $s_name);
		
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
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
    public static function getStatuses()
	{
		return self::STATUSES;
	}
	
    public function get_listcategories($id_lang=1){

        $this->select($this->table.'.id,'.$this->table.'.parent_id,'.$this->table_description.'.name,'.$this->table_description.'.description_short,'.$this->table_description.'.description,'.$this->table_description.'.link_rewrite,'.$this->table.'.img')
        ->table($this->table)
        ->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
        ->where($this->table_description.'.id_lang',$id_lang);

        $categories = $this->findAll();
        $data=[];
        foreach($categories as $i){
            $data[(string)$i['id']] = ['id'=>$i['id'],'name'=>$i['name'],'description_short'=>$i['description_short'],'description'=>$i['description'],'link_rewrite'=>$i['link_rewrite']];
        }
        return $data;
    }
public function get_categories($parent_id=0,$status=false,$id_lang=1){
    $this->select($this->table.'.id,'.$this->table.'.parent_id,'.$this->table_description.'.name,'.$this->table_description.'.description_short,'.$this->table_description.'.description,'.$this->table_description.'.link_rewrite,'.$this->table.'.img')
    ->table($this->table)
    ->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
    ->where($this->table.'.parent_id', $parent_id)
    ->where($this->table_description.'.id_lang',$id_lang)
    ->orderBy($this->table.'.position ASC,'.$this->table_description.'.name ASC');
    if($status)
        $this->where($this->table.'.status', $status);
    $categories = $this->findAll();
    $data=[];
    foreach($categories as $i){
        $data[(string)$i['id']] = ['id'=>$i['id'],'parent_id'=>$i['parent_id'],'name'=>$i['name'],'description_short'=>$i['description_short'],'description'=>$i['description'],'link_rewrite'=>$i['link_rewrite'],'img'=>$i['img'],'sub'=>$this->sub_categories($i['id'],$status,$id_lang)];
    }
    return $data;
}
public function sub_categories($id,$status=false,$id_lang=1){
    $this->select($this->table.'.id,'.$this->table.'.parent_id,'.$this->table_description.'.name,'.$this->table_description.'.description_short,'.$this->table_description.'.description,'.$this->table_description.'.link_rewrite,'.$this->table.'.img')
    ->table($this->table)
    ->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
    ->where($this->table.'.parent_id', $id)
    ->where($this->table_description.'.id_lang',$id_lang)
    ->orderBy($this->table.'.position ASC,'.$this->table_description.'.name ASC');
    if($status)
        $this->where($this->table.'.status', $status);
    $categories = $this->findAll();
    $data=[];
    foreach($categories as $i){
        $data[(string)$i['id']] = ['id'=>$i['id'],'parent_id'=>$i['parent_id'],'name'=>$i['name'],'description_short'=>$i['description_short'],'description'=>$i['description'],'link_rewrite'=>$i['link_rewrite'],'img'=>$i['img'],'sub'=>$this->sub_categories($i['id'],$status)];
    }
    return $data;
}
public function generateallparents($id='')
{
    $this->select('id')->table('tbl_category');
    if($id!='')
        $this->where('id', $id);
    $data = $this->limit(5000)->find();
    $udata=[];
    foreach ($data as $k => $v) {
        $this->tmp=[];
        $parents=$this->checkParentIds($v['id']);
        $parents_ids=implode(",",array_reverse(array_keys($parents)));
        $udata[]=['id'=>$v['id'],'parents'=>$parents_ids];
    }
    if(count($udata))
        $this->updateBatch($udata, 'id');
}
public function checkParentIds($id) {
        $this->select('id,parent_id')
        ->table('tbl_category')
        ->where('id', $id);
        $category = $this->first();
        if (isset($category['parent_id']) && $category['parent_id']>2) {
            $this->tmp[$category['id']] = $category;
            $this->checkParentIds($category['parent_id']);
        }
        return $this->tmp;
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
}
