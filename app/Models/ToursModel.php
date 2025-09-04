<?php namespace App\Models;
use CodeIgniter\Model;
class ToursModel extends Model
{
    protected $table      = 'tbl_tour';
	protected $primaryKey = 'id';
	//ALTER TABLE `tbl_tour` ADD `custom_sendemail` TINYINT UNSIGNED NULL AFTER `custom_contact`; 
	protected $allowedFields = ['id','artists_id','slug','img' ,'img_e','status','custom_email','custom_contact','custom_sendemail','custom_lastdatetime_sendemail'];
	protected $table_description = 'tbl_tour_lang';
	protected $table_descriptionKey = 'tour_id';
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];
	const IMAGE_SIZES = [
		'large' => [
			'width' => 1300,
			'height' => 300,
		],
		'medium' => [
			'width' => 308,
			'height' => 190,
		],
		'small' => [
			'width' => 100,
			'height' => 62,
		],
	];
   
	public  function getdata($nb_page, $page,$lang,$s_name='')
    {
        $this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*,tbl_artists.name as artist_name,tbl_artists.slug as artist_slug")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->join('tbl_artists', 'tbl_artists.id = '.$this->table.'.artists_id', 'left')
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
    public  function getlist($lang=1)
    {
        $data=[''=>'-'];
		$this->table($this->table)
        ->select($this->table.".id,".$this->table_description.".name")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang])
        ->orderBy($this->table.'.position','DESC');
       foreach ($this->find() as $row)
            $data[$row['id']] = $row['name'];
        return $data;
    }
	
	public  function list()
    {
       $data=[''=>'-'];
       foreach ($this->query('SELECT t.*,tl.name,a.name as artist_name FROM tbl_tour as t 
	   inner join tbl_tour_lang as tl on tl.tour_id=t.id  and tl.id_lang=1
	   left join tbl_artists as a on a.id=t.artists_id')->getResult('array') as $row)
            $data[$row['id']] = $row['artist_name'].' ['.$row['name'].']';
        return $data;
    }
	public  function getfront($artist_slug,$tour_slug,$lang_id){
        return $this->table('tbl_tour')
        ->select("tbl_tour.*,tbl_tour_lang.*,tbl_artists.name as artist_name,tbl_artists.slug as artist_slug")
        ->join('tbl_tour_lang', 'tbl_tour_lang.tour_id = tbl_tour.id', 'left')
        ->join('tbl_artists', 'tbl_artists.id = tbl_tour.artists_id', 'left')
        ->where(['tbl_artists.slug'=>$artist_slug,'tbl_tour.slug'=>$tour_slug,'tbl_tour_lang.id_lang'=>$lang_id])
        ->first();
	}
}
