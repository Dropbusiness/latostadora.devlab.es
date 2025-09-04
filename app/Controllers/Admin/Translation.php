<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Translation extends BaseController
{
    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'user-role';
        $this->data['currentAdminSubMenu'] = 'translation';
       
    }
    public function listing()
    {
        return view('admin/translation/listing', $this->data);
    }

    public function folderList()
    {
        if ($this->request->isAJAX()){
            $lang = $this->request->getPost('lang');
            helper('filesystem');
            $folder = directory_map(APPPATH . 'Language/' . $lang);
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Modificación correcta',
                'view'    => view('admin/translation/partials/folder-list', [
                    'folder_list' => $folder,
                    'lang' => $lang
                ])
            ]);
        }
        return $this->response->setJSON([
            'status' => false,
            'message' => 'No es adecuado para esta solicitud.'
        ]);
    }

    public function files($lang, $folder)
    {
        helper('filesystem');
        $files = directory_map(APPPATH . 'Language/' . $lang . '/' .$folder);
        $this->data['files']=$files;
        $this->data['path']=APPPATH . 'Language/'.$lang.'/'.$folder.'/';
        $this->data['lang']=$lang;
        $this->data['folder']=$folder;
        return view('admin/translation/files',$this->data);
    }

    public function translate($lang, $folder, $file)
    {
        $path = APPPATH . 'Language/' . $lang . '/' . $folder . '/' . $file . '.php';

        if ($this->request->getMethod() == 'post'){

            $translate = $this->request->getPost('translate');

            $str = "";
            foreach ($translate['text'] as $key => $value){
                $str .= "'".addslashes($key)."' => '".addslashes($value)."', \n";
            }


            $text = "<?php 
            return [
                'title' => '".addslashes($translate["title"])."',
                'description' => '".addslashes($translate["description"])."',
                    'text' => [
                        ".$str."
                    ]
                ];";

            $t_file = fopen($path, 'w');
            fwrite($t_file, $text);
            fclose($t_file);

            return redirect()->back()->with('success', 'La traducción se ha realizado correctamente.');
        }

        $strings = include $path;
        $this->data['strings']=$strings;
        $this->data['lang']=$lang;
        $this->data['folder']=$folder;
        $this->data['file']=$file;
        return view('admin/translation/translate',$this->data);
    }
}