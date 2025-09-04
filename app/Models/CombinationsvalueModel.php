<?php namespace App\Models;
use CodeIgniter\Model;
class CombinationsvalueModel extends Model
{
    protected $table      = 'tbl_combinations_value';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['combination_id','attribute_id', 'value_id']; // los campos que se insertarán

    protected $useTimestamps = false; // cambia esto según tu tabla

    // Reglas de validación y mensajes de error
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    public function insertCombinationValue($data)
    {
        // Inserta el valor de la combinación
        $this->insert($data);
    }

}
