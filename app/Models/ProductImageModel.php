<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
	protected $table      = 'tbl_product_images';
	protected $primaryKey = 'id';


	protected $allowedFields = [
		'product_id',
		'img',
		'cover',
		'thum',
		'created_at',
		'updated_at',
		'position'
	];

	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';

	protected $validationRules    = [];

	protected $validationMessages = [];
	protected $skipValidation     = false;

	const IMAGE_SIZES = [
		'large' => [
			'width' => 1440,
			'height' => 2160,
		],
		'medium' => [
			'width' => 500,
			'height' => 750,
		],
		'small' => [
			'width' => 70,
			'height' => 105,
		],
	];
	public  function imgdelete($product_id)
    {
        $this->table('tbl_product_images')
        ->select("id,img")
		->where('product_id',$product_id);
		$patch='./uploads/products/';
        foreach ($this->get()->getResult('array') as $result) {
			@unlink($patch."original/".$result['img']);
			@unlink($patch."medium/".$result['img']);
			@unlink($patch."large/".$result['img']);
			@unlink($patch."small/".$result['img']);
        }
		$this->table('tbl_product_images')->where('product_id',$product_id)->delete();
		$this->query('UPDATE `tbl_product` SET `img` = NULL WHERE `id` ='.$product_id);
    }
	#conver predeteminado si en caso en el producto no existe la imagen predeteminado cover=1
	public function setdefaultimg($product_id)
	{
		$img = $this->table('tbl_product_images')
		->where('product_id',$product_id)
		->where('cover',1)
		->first();
		if(!$img)
		{
			 $this->table('tbl_product_images')
			->where('product_id',$product_id)
			->set('cover',1)
			->limit(1)
			->update();
			#en la tabla tbl_product agregar la imagen por defecto
			$image_default = $this->table('tbl_product_images')
			->where('product_id',$product_id)
			->where('cover',1)
			->first();
			if($image_default)
			{
				$this->query('UPDATE `tbl_product` SET `img` = "'.$image_default['img'].'" WHERE `id` ='.$product_id);
			}
		}
	}
	#retornar la nueva position por defecto, tiene que ser incrementable mas uno y no existe retornamos 1
	public function getNewPosition($product_id)
	{
		$position = $this->table('tbl_product_images')
		->where('product_id',$product_id)
		->select('MAX(position) as position')
		->first();
		if(!$position)
		{
			return 1;
		}
		return $position['position']+1;
	}
}
