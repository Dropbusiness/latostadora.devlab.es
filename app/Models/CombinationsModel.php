<?php namespace App\Models;
use CodeIgniter\Model;
class CombinationsModel extends Model
{
    protected $table      = 'tbl_combinations';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['product_id', 'price', 'stock', 'reference', 'ean','default_on','models_code']; // los campos que se insertarán

    protected $useTimestamps = false; // cambia esto según tu tabla
    // Si usas timestamps, especifica las columnas 'created_at' y 'updated_at'

    // Reglas de validación y mensajes de error
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    public function insertCombination($data)
    {
        // Inserta la combinación y devuelve el ID insertado
        $this->insert($data);
        return $this->insertID();
    }
    public function saveAttributes($combination_id, $product_id, $data)
        {
            // Define los datos que se van a actualizar
            $attributesData = [
                'price' => $data['price'],
                'stock' => $data['stock'],
                'reference' => $data['reference'],
                'ean' => $data['ean'],
                'default_on' => $data['default_on'],
                'models_code' => isset($data['models_code'])?trim($data['models_code']):'',
                // Agrega aquí otros atributos que desees actualizar
            ];

            // Luego, actualiza los atributos en la tabla de combinaciones
            $this->db->table('tbl_combinations')
                ->where('product_id', $product_id)
                ->where('id', $combination_id)
                ->update($attributesData);

            // Puedes agregar lógica adicional aquí, como manejar relaciones o guardar registros en otras tablas si es necesario.

            // Devuelve true o false dependiendo del resultado de la operación.
            return true; // O false en caso de error
        }
    public function resetDefaultCombination($product_id)
        {
            // Establece el valor 'default_on' en 0 para todas las combinaciones del producto
            $this->where('product_id', $product_id)
                 ->set('default_on', 0)
                 ->update();
        } 
    #definimos siempre un default_on=1 por defecto siempre en cuando no exista ninguna para el producto
        public function setDefaultOneCombination($product_id)
        {
            #comprobar si existe alguna combinación con default_on=1, si no existe la creamos en la primera posición 
            $combination = $this->where('product_id', $product_id)
                 ->where('default_on', 1)
                 ->first();
            if(!$combination)
            {
                $this->where('product_id', $product_id)
                    ->orderBy('id', 'ASC')
                    ->limit(1)
                    ->set('default_on', 1)
                    ->update();
            }
        }
}
