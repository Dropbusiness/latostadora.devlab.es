<?php namespace App\Models;
use CodeIgniter\Model;
class LanguageModel extends Model
{
    protected $table      = 'languages';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id', 'name','code','img', 'status','position', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];


	public  function getdata($nb_page, $page,$s_name='')
    {
        $this->table('languages')
        ->select("languages.*")
        ->orderBy('position','ASC');
		if($s_name!='')
			$this->like('languages.name', $s_name);
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public  function languages()
    {
        $data_code=$data_ids=[];
        foreach ($this->db->query("SELECT id,name,code,img FROM languages order by position asc")->getResultArray() as $r){
            $data_code[$r['code']]=$r;
            $data_ids[$r['id']]=$r;
        }
        return ['codes'=>$data_code,'ids'=>$data_ids];
    }
    public static function getStatuses()
	{
		return self::STATUSES;
	}
    public  function listlanguages()
    {
        $data=[''=>'-'];
       foreach ($this->query('SELECT id,name FROM `languages`')->getResult('array') as $row)
            $data[$row['id']] = $row['name'];
        return $data;
    }

}
