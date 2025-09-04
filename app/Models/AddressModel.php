<?php namespace App\Models;
use CodeIgniter\Model;
class AddressModel extends Model
{
    protected $table      = 'tbl_address';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id','id_customer', 'name', 'address', 'postcode', 'city', 'state','country', 'phone','usercode','erp_addressID','status'];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];
	public  function getdata($nb_page, $page,$string='')
    {
        $this->table('tbl_address')
        ->select("tbl_address.*")
        ->orderBy('id','DESC');
        if($string!='')
			$this->groupStart()
				->orLike(['name' => $string, 'address' => $string, 'usercode' => $string])
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


}
