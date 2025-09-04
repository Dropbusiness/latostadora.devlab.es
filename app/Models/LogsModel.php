<?php namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model
{
	protected $table      = 'tbl_logs';
	protected $primaryKey = 'id';

	protected $returnType = 'array';
	protected $useSoftDeletes = false;

	// this happens first, model removes all other fields from input data
	protected $allowedFields = [
		'date', 'time','target','id_lang', 'reference', 'name', 'ip', 'location', 'browser', 'status'
	];

	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $dateFormat  	 = 'datetime';

	protected $validationRules = [];

	// we need different rules for logs
	protected $dynamicRules = [
		'logs' => [
			'date'	=> 'required',
			'time'	=> 'required',
			'target'	=> 'required',
			'id_lang'	=> 'required',
			'reference'	=> 'required',
			'ip'	=> 'required',
			'location'	=> 'required',
			'browser'	=> 'required',
			'status'	=> 'required'
		]
	];

	protected $validationMessages = [];

	protected $skipValidation = false;


    //--------------------------------------------------------------------

    /**
     * Retrieves validation rule
     */
	public function getRule(string $rule)
	{
		return $this->dynamicRules[$rule];
	}

}
