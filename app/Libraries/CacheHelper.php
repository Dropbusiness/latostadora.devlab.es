<?php

namespace App\Libraries;

use App\Models\ConfigurationModel;
use App\Models\CategoryModel;
use App\Models\BrandModel;
use App\Models\PageModel;
class CacheHelper
{
    private $lang_id;
    private $configurationModel;
    private $categoryModel;
    private $brandModel;
    private $pageModel;
    public function __construct($lang_id)
    {
        $this->lang_id = $lang_id;
        $this->configurationModel = new ConfigurationModel();
        $this->categoryModel = new CategoryModel();
        $this->brandModel = new BrandModel();
        $this->pageModel = new PageModel();
    }

    public function getCachedData()
    {
        if (getenv('cache.active') == 'true' && getenv('cache.ttl')) {
            $data['cat_tree'] = $this->setCacheData('cat_tree', $this->categoryModel, 'get_categories', [18, true, $this->lang_id]);
            $data['cat_list'] = $this->setCacheData('cat_list', $this->categoryModel, 'get_listcategories', [$this->lang_id]);
            $data['configuration'] = $this->setCacheData('configuration', $this->configurationModel, 'getallconfiguration', [$this->lang_id]);
            $data['allpage'] = $this->setCacheData('allpage', $this->pageModel, 'getallpage', [$this->lang_id]);
            $data['brands_all'] = $this->setCacheData('brands_all', $this->brandModel, 'getallbrands', [$this->lang_id]);
        } else {
            $data['cat_tree'] = $this->categoryModel->get_categories(18, true, $this->lang_id);
            $data['cat_list'] = $this->categoryModel->get_listcategories($this->lang_id);
            $data['configuration'] = $this->configurationModel->getallconfiguration($this->lang_id);
            $data['allpage'] = $this->pageModel->getallpage($this->lang_id);
            $data['brands_all'] = $this->brandModel->getallbrands($this->lang_id);
        }
        return $data;
    }

    private function setCacheData($key, $model, $method, $params = [])
    {
        // Añade el idioma a la clave de la cache
        $key = "{$key}_{$this->lang_id}";

        if (!$data = cache($key)) {
            // Llama al método del modelo y pasa los parámetros
            $data = call_user_func_array([$model, $method], $params);
            cache()->save($key, $data, getenv('cache.ttl'));
        }

        return $data;
    }
}
