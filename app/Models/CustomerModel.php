<?php namespace App\Models;
use CodeIgniter\Model;
class CustomerModel extends Model
{
    protected $table      = 'tbl_customer';
	protected $primaryKey = 'id';
	protected $allowedFields = [
		'id', 
		'cif', 
		'firstname', 
		'lastname', 
		'company', 
		'country', 
		'city', 
		'address', 
		'phone',
		'cp',
		'email', 
		'passwd', 
		'status', 
		'created_at', 
		'updated_at', 
		'password_token',
		'optin'
	];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
  
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
        $this->table('tbl_customer')
        ->select("tbl_customer.*")
        ->orderBy('id','DESC');
		if($string!='')
			$this->groupStart()
				->orLike([
					'company' => $string, 
				'firstname' => $string, 
				'usercode' => $string, 
				'email' => $string, 
				'cif' => $string])
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
    /*protected function hashPassword(array $data)
	{
		if (! isset($data['data']['passwd'])) return $data;
		$data['data']['passwd'] = password_hash($data['data']['passwd'], PASSWORD_DEFAULT);
		return $data;
	}*/


}
