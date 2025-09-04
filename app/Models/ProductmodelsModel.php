<?php namespace App\Models;
use CodeIgniter\Model;
class ProductmodelsModel extends Model
{
    protected $table      = 'tbl_product_models';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id','code', 'name', 'color', 'talla'];
	public  function getdata($nb_page, $page,$string='')
    {
        $this->table('tbl_product_models')
        ->select("tbl_product_models.*")
        ->orderBy('id','DESC');
        if($string!='')
			$this->groupStart()
				->orLike(['name' => $string, 'color' => $string, 'talla' => $string])
				->groupEnd();
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public function getlist()
    {
        $data=[];
        $this->query('SELECT id,code,name,color,talla FROM tbl_product_models')->getResult('array');
        foreach ($this->get()->getResult('array') as $result)
            $data[trim($result['code'])] = ['id'=>trim($result['id']),'code'=>trim($result['code']),'name'=>trim($result['name']),'color'=>trim($result['color']),'talla'=>trim($result['talla'])];
        return $data;
    }
}
