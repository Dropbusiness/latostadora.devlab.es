<?php namespace App\Models;
use CodeIgniter\Model;
class ArtistsModel extends Model
{
    protected $table      = 'tbl_artists';
	protected $primaryKey = 'id';
	protected $allowedFields = [
		'id', 
		'name',
        'slug', 
		'status', 
	];
  
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];
	public function setter($id){
        $reg=($id>0?$this->find($id):[]);
		$array_cols=$this->db->getFieldNames($this->table);
        $data=array();
        foreach($array_cols as $c) $data[$c]=(isset($reg[$c])?$reg[$c]:'');
        return $data;
    }
    public  function getdata($nb_page, $page,$string='')
    {
        $this->table('tbl_artists')
        ->select("tbl_artists.*")
        ->orderBy('id','DESC');
		if($string!='')
			$this->groupStart()
				->orLike([
					'name' => $string, 
				'slug' => $string])
				->groupEnd();
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public static function getStatuses()
	{
		return self::STATUSES;
	}

	public  function list()
    {
       $data=[''=>'-'];
       foreach ($this->query('SELECT * FROM `tbl_artists` ORDER BY `name`')->getResult('array') as $row)
            $data[$row['id']] = $row['name'];
        return $data;
    }

}
