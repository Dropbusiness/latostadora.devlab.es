<?php namespace App\Models;
use CodeIgniter\Model;
class ConfigurationModel extends Model
{
    protected $table      = 'tbl_configuration';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id', 'name', 'value'];
    protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
    public  function getdata($nb_page, $page)
    {
        $this->table('tbl_configuration')
        ->select("tbl_configuration.*")
        ->orderBy('id','ASC');
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public  function getallconfiguration($id_lang=1)
    {
        $data=[];
        $this->table('tbl_configuration')
        ->select("tbl_configuration.*");
        foreach ($this->get()->getResult('array') as $result) {
            $data[$result['name']] = $result['value'];
        }
        return $data;
    }
}
