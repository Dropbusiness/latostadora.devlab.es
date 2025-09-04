<?php namespace App\Models;
use CodeIgniter\Model;
class ProductlangModel extends Model
{
    protected $table      = 'tbl_product_lang';
	protected $primaryKey = 'PRIMARY';
	protected $allowedFields = [ 'product_id', 'id_lang', 'description_short', 'description','quality','n_scientist	','d_use'];
}

