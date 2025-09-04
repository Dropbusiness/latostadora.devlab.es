<?php namespace App\Models;
use CodeIgniter\Model;
class OrderdetailsModel extends Model
{
    protected $table      = 'tbl_order_details';
	protected $primaryKey = 'id';
	protected $allowedFields = [
        'id',
        'order_id',
        'product_id',
        'combination_id',
        'product_name',
        'product_sku',
        'product_price',
        'product_quantity',
        'tax_id',
        'tax_val',
        'events_id',
        'tour_id',
    ];
    public  function gettopproducts($date_from, $date_to, $nb_page, $page)
    {
        $this->table('tbl_order_details')
        ->select("product_id,product_sku,product_name,COUNT(*) as total")
      //  ->where('tbl_order_details.`server_time` BETWEEN "' . ($date_from) . ' 00:00:00" AND "' . ($date_to) . ' 23:59:59"')
        ->groupBy('tbl_order_details.product_sku')
        ->orderBy('total','DESC');
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
}
