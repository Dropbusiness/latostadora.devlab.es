<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
class Dashboard extends BaseController
{
    protected $orderModel;
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->session = \Config\Services::session();
        $this->session->start();
        $newdata = [
            'date_start'  => $this->session->has('date_start')?$this->session->get('date_start'):date('Y-m-d', strtotime('-1 month')),
            'date_end'     => $this->session->has('date_end')?$this->session->get('date_end'):date('Y-m-d'),
        ];
        $this->session->set($newdata);
        $this->data['date_start'] = $newdata['date_start'];
        $this->data['date_end'] = $newdata['date_end'];
    }
   
    function index()
    {
        $this->data['pageTitle'] = 'Dashboard Pages';
        $this->data['currentAdminMenu'] = 'dashboard';
        $this->data['currentAdminSubMenu'] = 'dashboard';
        return view('admin/dashboard/index', $this->data);
    }
    public function toolsjson()
    {
        $date_from=$this->session->has('date_start')?$this->session->get('date_start'):date('Y-m-d', strtotime('-1 month'));
        $date_to=$this->session->has('date_end')?$this->session->get('date_end'):date('Y-m-d');
        $act=$this->request->getVar('act');
        $page=$this->request->getVar('page')?$this->request->getVar('page'):1;
        $nb_page=7;
        $granularity='day';
        switch ($act) {
            /***************casos página home******************/
            case 'setconfigsearch':
                $data = [
                    'date_start'  => $this->request->getVar('date_start')?$this->request->getVar('date_start'):date('Y-m-d', strtotime('-1 month')),
                    'date_end'     => $this->request->getVar('date_end')?$this->request->getVar('date_end'):date('Y-m-d'),
                ];
                $this->session->set($data);
                $result=['success' => true];
            break;
            case 'getalltotals':
                $data=$this->orderModel->getAllTotals();
                $result = [
                    'success' => true,
                    'data' => $data
                ];
            break;
            case 'getallcart':
                $data=$this->orderModel->getAllCart($date_from, $date_to,  $granularity);
                $result = [
                    'success' => true,
                    'data' => $data
                ];
            break;
            case 'getcontacts':
                $contactModel = new \App\Models\ContactModel();
                $nb_page=8;
                $data=$contactModel->getcontacts($date_from, $date_to,$nb_page,$page, [1]);
                $result = [
                    'success' => true,
                    'data_list' => $data['data'],
                    'data_pager' => $data['pager']->links('default','bootstrap_pagination'),
                ];
            break;
            case 'getincidencias':
                $contactModel = new \App\Models\ContactModel();
                $nb_page=8;
                $data=$contactModel->getcontacts($date_from, $date_to,$nb_page,$page, [2,3,4,5,6,7]);
                $result = [
                    'success' => true,
                    'data_list' => $data['data'],
                    'data_pager' => $data['pager']->links('default','bootstrap_pagination'),
                ];
            break;
            case 'getorders':
                $nb_page=8;
                $data=$this->orderModel->getdata($nb_page,$page,'',[3,4,5],'',$date_from, $date_to);
                $result = [
                    'success' => true,
                    'data_list' => $data['data'],
                    'data_pager' => $data['pager']->links('default','bootstrap_pagination'),
                ];
            break;
            case 'gettopproducts':
               $orderdetailsModel = new \App\Models\OrderdetailsModel();
               $nb_page=8;
               $data=$orderdetailsModel->gettopproducts($date_from,$date_to,$nb_page,$page);
                $result = [
                    'success' => true,
                    'data_list' => $data['data'],
                    'data_pager' => $data['pager']->links('default','bootstrap_pagination'),
                ];
            break;
            default:
                 $result=[];
                break;
        }
       return $this->response->setJSON($result);
    }
}
