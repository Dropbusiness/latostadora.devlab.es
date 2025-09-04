<?php namespace App\Models;
use CodeIgniter\Model;
class ContactModel extends Model
{
    protected $table      = 'tbl_contact';
	protected $primaryKey = 'id';
	protected $allowedFields = [
        'id', 
        'first_name', 
        'last_name', 
        'status', 
        'message', 
        'phone', 
        'email', 
        'ctype', 
        'customer_id', 
        'ip',
        'optin'];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
    const STATUSES = [
		'1' => 'Active',
		'2' => 'Inactive',
	];

    const CTYPE = [
        '1' => 'Contacto',
		'2' => 'Reclamaciones',
		'3' => 'Reparaciones',
        '4' => 'Devoluciones',
        '5' => 'Reclamación contable',
        '6' => 'Problema con la web',
        '7' => 'Escribe tu sugerencia',
	];
    public  function getdata($nb_page, $page,$string='')
    {
        $this->table('tbl_contact')
        ->select("tbl_contact.*")
        ->orderBy('id','DESC');
        if($string!='')
			$this->groupStart()
				->orLike(['first_name' => $string, 'last_name' => $string, 'phone' => $string, 'email' => $string, 'customer_id' => $string])
				->groupEnd();
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public  function getcontacts($date_from, $date_to,$nb_page, $page,$ctype='')
    {
        $this->table('tbl_contact')
        ->select("id, first_name, last_name, phone, email, created_at,SUBSTRING(message,1,50) AS msg")
        ->where('tbl_contact.`created_at` BETWEEN "' . ($date_from) . ' 00:00:00" AND "' . ($date_to) . ' 23:59:59"')
        ->orderBy('created_at','DESC');
        if($ctype!='' || is_array($ctype)){
			$sql=is_array($ctype)?"tbl_contact.ctype in ('".implode("','",$ctype)."')":"tbl_contact.ctype = '".(int)$ctype."'";
			$this->where($sql);
		}
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public static function getStatuses()
	{
		return self::STATUSES;
	}
    public static function getCtype()
	{
		return self::CTYPE;
	}


}
