<?php namespace App\Models;

use CodeIgniter\Model;
use Elasticsearch\ClientBuilder;
class ProductModel extends Model
{
    protected $table      = 'tbl_product';
	protected $primaryKey = 'id';
	protected $allowedFields = [
		'id', 
		'user_id', 
		'brand_id', 
		'status', 
		'sku', 
		'price', 
		'stock', 
		'id_category_default', 
		'ean', 
		'reference', 
		'minimal_quantity', 
		'img', 
		'created_at', 
		'updated_at', 
		'deleted_at', 
		'erp_id', 
		'recommendation', 
		'position',
		'group_showsize'
	];
	protected $table_description = 'tbl_product_lang';
	protected $table_descriptionKey = 'product_id';

	protected $useTimestamps = true;
	protected $useSoftDeletes = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';


	const STATUSES = [
		'0' => 'Papelera',
		'1' => 'Activo',
		'2' => 'Desactivado',
	];
	const GROUP_SHOWSIZE = [
		'0' => 'Ninguno',
		'1' => 'Camiseta'
	];
	public static function getStatuses()
	{
		$statuses = array_merge(
			[
				'' => '-- Set Status --'
			],
			self::STATUSES
		);

		return $statuses;
	}
	public static function getGroupShowSize()
	{
		$statuses = self::GROUP_SHOWSIZE;
		return $statuses;
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
	public  function getdata($nb_page,$page,$lang,$string='',$categories='',$brand='',$s_status='',$s_events='')
    {

		$this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang])
		->orderBy($this->table.'.position ASC',$this->table.'.id DESC');
		if($categories!='' || is_array($categories)){
			$sql=is_array($categories)?"tbl_product_categories.category_id in ('".implode("','",$categories)."')":"tbl_product_categories.category_id = '".(int)$categories."'";
			$this->join('tbl_product_categories', 'tbl_product_categories.product_id=tbl_product.id', 'left')
			->where($sql)
			->groupBy(['tbl_product.id']);
		}
		if($s_events!=''){
			$sql="tbl_product_events.events_id = '".(int)$s_events."'";
			$this->join('tbl_product_events', 'tbl_product_events.product_id=tbl_product.id', 'left')
			->where($sql)
			->groupBy(['tbl_product.id']);
		}
		if($brand!='')
			$this->where('tbl_product.brand_id ', $brand);

		if($s_status!='' || is_array($s_status)){
			$sql=is_array($s_status)?$this->table.".status in ('".implode("','",$s_status)."')":$this->table.".status = '".(int)$s_status."'";
			$this->where($sql);
		}
		
		if($string!='')
			$this->groupStart()
				->orLike(['tbl_product.sku' => $string, 'tbl_product_lang.name' => $string, 'tbl_product.sku' => $string])
				->groupEnd();
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
	public  function getitem($product_id='',$link_rewrite='',$lang=1)
    {
        $this->table($this->table)
        ->select($this->table.".*,".$this->table_description.".*")
		->join($this->table_description, $this->table_description.'.'.$this->table_descriptionKey.' = '.$this->table.'.'.$this->primaryKey.'', 'left')
		->where([$this->table_description.'.id_lang'=>$lang]);
		if($product_id!='')
			$this->where($this->table.'.id', $product_id);
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
	public  function getproducts($nb_page,$page,$id_lang=1,$string='',$categories='',$brand='',$reference='',$limit=0,$events_id='')
    {
        $this->table('tbl_product')
        ->select("tbl_product.id,tbl_product.brand_id,tbl_product.id_category_default,tbl_product.sku,tbl_product_lang.name,tbl_product_lang.link_rewrite,tbl_product.price,tbl_product.stock,tbl_product.img,tbl_product.minimal_quantity")
		->join('tbl_product_lang', 'tbl_product_lang.product_id=tbl_product.id', 'left')
		->where('tbl_product.status ', 1)
		->where('tbl_product_lang.id_lang', $id_lang)
        ->orderBy($this->table.'.position ASC',$this->table.'.id DESC');
		if($categories!='' || is_array($categories)){
			$sql=is_array($categories)?"tbl_product_categories.category_id in ('".implode("','",$categories)."')":"tbl_product_categories.category_id = '".(int)$categories."'";
			$this->join('tbl_product_categories', 'tbl_product_categories.product_id=tbl_product.id', 'left')
			->where($sql)
			->groupBy(['tbl_product.id']);
		}
		if($reference!='' || is_array($reference)){
			$sql=is_array($reference)?"tbl_product.sku in ('".implode("','",$reference)."')":"tbl_product.sku = '".(string)$reference."'";
			$this->where($sql);
		}
		if($brand!='')
			$this->where('tbl_product.brand_id ', $brand);

		if($string!='')
			$this->groupStart()
				->orLike(['sku' => $string, 'name' => $string])
				->groupEnd();
		if($events_id>0)
			$this->join('tbl_product_events', 'tbl_product_events.product_id=tbl_product.id', 'left')
			->where('tbl_product_events.events_id ', $events_id);
		if($limit>0){
			 return $this->limit($limit)->find();
		}else{
			return [
				'data'  => $this->paginate($nb_page,'default',$page),
				'pager'     => $this->pager,
				'sql'     => (string)$this->getLastQuery(),
			];
		}
    }
	function catrelation($product_id=''){
		$rdata=[];
        $data=$this->query('SELECT category_id FROM `tbl_product_categories` as pc where product_id='.(int)$product_id)->getResult('array');
		foreach ($data as $k => $v)
			$rdata[$v['category_id']]=$v['category_id'];
		return $rdata;
    }
	function eventrelation($product_id=''){
		$rdata=[];
        $data=$this->query('SELECT events_id FROM `tbl_product_events`  where product_id='.(int)$product_id)->getResult('array');
		foreach ($data as $k => $v)
			$rdata[$v['events_id']]=$v['events_id'];
		return $rdata;
    }
    function getfeed($brand_id=''){
        $conditions='';
        $conditions.=($brand_id>0?' AND p.brand_id = '.(int)$brand_id:'');
        return $this->query(
            'SELECT p.id,p.sku,p.name,p.img,p.slug,p.iva,p.description,p.ean,p.price,p.stock,p.status,p.created_at,p.updated_at,p.brand_id,p.id_category_default,b.name as brand_name,c.name as category_name FROM `tbl_product` as p
            LEFT JOIN tbl_brand as b on b.id=p.brand_id
            LEFT JOIN tbl_category as c on c.id=p.id_category_default '.($conditions!=''?' where true'.$conditions:'')
        )->getResult('array');
    }
	public  function getfeatures($id_product)
    {
		$fp=[];
		foreach ($this->query('SELECT * FROM tbl_feature_product WHERE id_product='.(int)$id_product)->getResult('array') as $k => $v)
			$fp[$v['id_feature']][$v['id_feature_value']]=$v;

		$fv=[];
		foreach ($this->query('SELECT fv.id,fv.id_feature,fvl.name,fvl.name FROM tbl_feature_value as fv 
		inner join tbl_feature_value_lang as fvl on fvl.feature_value_id=fv.id where fvl.id_lang=1 order by fvl.name')->getResult('array') as $k => $v)
			$fv[$v['id_feature']][]=$v+['selected'=>(isset($fp[$v['id_feature']][$v['id']])?1:0)];
	
		$f=[];
		foreach ($this->query('SELECT f.id,fl.name FROM tbl_feature as f 
		inner join tbl_feature_lang as fl on fl.feature_id=f.id where fl.id_lang=1 order by f.position')->getResult('array') as $k => $v){
			$f[$v['id']]=['feature_id' => $v['id'], 'feature_name' =>$v['name'], 'values' =>(isset($fv[$v['id']])?$fv[$v['id']]:[])];
		}
			return $f;
    }
	public  function getallattributes()
    {
		
		$av=[];
		foreach ($this->query('SELECT av.id,av.attribute_id,avl.name,avl.name FROM tbl_attributes_value as av 
		inner join tbl_attributes_value_lang as avl on avl.attribute_value_id=av.id where avl.id_lang=1 order by av.position ASC')->getResult('array') as $k => $v)
			$av[$v['attribute_id']][]=$v;
	
		$a=[];
		foreach ($this->query('SELECT a.id,al.name FROM tbl_attributes as a 
		inner join tbl_attributes_lang as al on al.attribute_id=a.id where al.id_lang=1 order by a.position')->getResult('array') as $k => $v){
			$a[$v['id']]=['id' => $v['id'], 'name' =>$v['name'], 'values' =>(isset($av[$v['id']])?$av[$v['id']]:[])];
		}
		return $a;
    }
	public  function attributes($product_id,$combination_id='',$id_lang=1)
    {
		$conditions='';
		$conditions.=($product_id?' and c.product_id ='.$product_id:'');
		$conditions.=($combination_id?' and c.id ='.$combination_id:'');
		return $this->query("SELECT 
			c.id AS combination_id,
			c.product_id,
			GROUP_CONCAT(CONCAT(al.name, ': ', avl.name) ORDER BY cv.attribute_id SEPARATOR ', ') AS combination_details,
			c.price,
			c.stock,
			c.reference,
			c.ean,
			c.models_code,
			c.default_on
			FROM 
				tbl_combinations AS c
			JOIN 
				tbl_combinations_value AS cv ON c.id = cv.combination_id
			JOIN 
				tbl_attributes_value_lang AS avl ON cv.value_id = avl.attribute_value_id
			JOIN 
				tbl_attributes_lang AS al ON cv.attribute_id = al.attribute_id
			WHERE 
				avl.id_lang = ".$id_lang."
				AND al.id_lang = ".$id_lang."
				".$conditions."
			GROUP BY 
				c.id, c.price, c.stock, c.reference, c.ean, c.default_on
			ORDER BY 
				c.id")->getResult('array');
    }
	public  function iattributes($product_id,$id_lang=1)
    {
		#lista completa de combinaciones
		$attributes=$this->query("SELECT 
		c.id AS combination_id, 
		c.reference, 
		c.ean, 
		c.price, 
		c.stock, 
		c.default_on, 
		cv.attribute_id, 
		cv.value_id,
		a.position as attribute_position,
		al.name AS attribute_name, 
		av.code AS value_code,
		avl.name AS value_name
	FROM 
		tbl_combinations c
	INNER JOIN 
		tbl_combinations_value cv ON c.id = cv.combination_id
	INNER JOIN 
		tbl_attributes_value av ON cv.value_id = av.id
	INNER JOIN 
		tbl_attributes_value_lang avl ON cv.value_id = avl.attribute_value_id AND avl.id_lang = $id_lang
	INNER JOIN 
		tbl_attributes a ON a.id = cv.attribute_id
	INNER JOIN 
		tbl_attributes_lang al ON cv.attribute_id = al.attribute_id AND al.id_lang =$id_lang
	WHERE
		c.product_id =$product_id
	ORDER BY a.position ASC,av.position ASC")->getResult('array');
		#lista de activado por defecto
		$defaulton = array_filter($attributes, function($elemento) {
			return $elemento['default_on'] == 1;
		});
		$combination_on=$acombination_ids=[];
		foreach ($defaulton as $k => $row) 
				$combination_on[$row['attribute_id'] . '_' . $row['value_id']] = true;

		#comprobar que combinaciones mostramos
		/*foreach ($attributes as $attribute)
			if (isset($combination_on[$attribute['attribute_id'] . '_' . $attribute['value_id']]))
				$acombination_ids[$attribute['combination_id']][] = true;
		$combination_on_n=count($combination_on)-1;
		$combination_ids = array_filter($acombination_ids, function ($item) use ($combination_on_n) {
			return count($item) >= $combination_on_n;
		});*/

		#lista agrupada por atributos y valores
		$grouped = [];
		foreach ($attributes as $attribute) {
				//if(!isset($combination_ids[$attribute['combination_id']]) && $attribute['attribute_id']!=1) continue;
				$attrId = $attribute['attribute_id'];
				$valueId = $attribute['value_id'];
				if (!isset($grouped[$attrId]))
					$grouped[$attrId] = $attribute + ['items' => []];

				$grouped[$attrId]['items'][$valueId] = $attribute;
				$grouped[$attrId]['items'][$valueId]['default_on'] = isset($combination_on[$attrId . '_' . $valueId])?1:0;
		}
		
		$data['attributes']=$attributes;
		$data['grouped']=$grouped;
		$data['defaulton']=$defaulton;
		return $data;
    }
	
	public  function combinationtxt($combination_id,$id_lang=1)
    {
		$data=$this->query("SELECT GROUP_CONCAT(concat_ws(': ',al.name,avl.name)  SEPARATOR ', ') AS name
		FROM 
			tbl_combinations_value cv
		INNER JOIN 
			tbl_attributes_value av ON cv.value_id = av.id
		INNER JOIN 
			tbl_attributes_value_lang avl ON cv.value_id = avl.attribute_value_id AND avl.id_lang = $id_lang
		INNER JOIN 
			tbl_attributes a ON a.id = cv.attribute_id
		INNER JOIN 
			tbl_attributes_lang al ON cv.attribute_id = al.attribute_id AND al.id_lang =$id_lang
		WHERE
			cv.combination_id =$combination_id
		GROUP BY cv.combination_id
		ORDER BY a.position ASC,av.position ASC")->getRowArray();
		return isset($data['name'])?$data['name']:'';
    }
	public  function icombination($product_id)
    {
		$combinations=$this->query("SELECT 
			c.id, 
			cv.attribute_id, 
			cv.value_id
		FROM 
			tbl_combinations c
		INNER JOIN 
			tbl_combinations_value cv ON c.id = cv.combination_id
		WHERE
		c.product_id = $product_id")->getResult('array');

		 $grouped = [];
		foreach ($combinations as $item)
			$grouped[$item['id']][] = $item['attribute_id'] . '|' . $item['value_id'];

		return $grouped;
    }
	public function get_guiaTalla($page_id, $id_lang)
	{
		return $this->db->table('tbl_page_lang')
			->where('page_id', $page_id)
			->where('id_lang', $id_lang)
			->get()
			->getRowArray();
	}
	public function get_combinations($combinations_ids)
	{
		if(count($combinations_ids)==0) return [];
		$alldata=$this->query("SELECT  pc.id,pc.product_id,pcv.attribute_id,pcv.value_id,fvl.name 
		FROM tbl_combinations_value pcv 
		inner join tbl_combinations as pc on pc.id=pcv.combination_id 
		left join tbl_attributes_value as fv on fv.id=pcv.value_id
		left join tbl_attributes_value_lang as fvl on fvl.attribute_value_id=fv.id and fvl.id_lang=1 
		where pcv.combination_id  in (".implode(',',$combinations_ids).")")->getResultArray();
		$data=[];
		foreach ($alldata as $k => $v)
			$data[$v['id']][$v['attribute_id']][] = $v;
		return $data;
	}
	public function updateModelsCode()
	{
		$sql = "UPDATE tbl_combinations 
				SET models_code = SUBSTRING(
					reference, 
					LOCATE('-', reference) + 1, 
					LOCATE('-', reference, LOCATE('-', reference) + 1) - LOCATE('-', reference) - 1
				)
				WHERE (models_code IS NULL OR models_code = '' OR TRIM(models_code) = '')
				AND reference LIKE '%-%-%'
				AND LENGTH(reference) - LENGTH(REPLACE(reference, '-', '')) >= 2";
		
		$this->db->query($sql);
		return $this->db->affectedRows();
	}
}
/*
ALTER TABLE `tbl_product` ADD `pricecopa` FLOAT NULL AFTER `coste`, ADD `costecopa` FLOAT NULL AFTER `pricecopa`; 
UPDATE tbl_product SET `pricecopa` = ROUND(price/4.5, 2),`costecopa` = ROUND(coste/4.5, 2);
ALTER TABLE `tbl_product` ADD `position` INT UNSIGNED NULL AFTER `ctipo`; 
*/
