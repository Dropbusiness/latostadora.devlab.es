<?php namespace App\Controllers\Admin;

class Auth extends \IonAuth\Controllers\Auth
{
    protected $viewsFolder = 'admin/auth';
    public function login()
	{
		$this->data['title'] = lang('Auth.login_heading');
		$this->validation->setRule('identity', str_replace(':', '', lang('Auth.login_identity_label')), 'required');
		$this->validation->setRule('password', str_replace(':', '', lang('Auth.login_password_label')), 'required');
		
		if ($this->request->getPost() && $this->validation->withRequest($this->request)->run())
		{
			
			$remember = (bool)$this->request->getVar('remember');
			if ($this->ionAuth->login($this->request->getVar('identity'), $this->request->getVar('password'), $remember))
			{
				$this->session->setFlashdata('message', $this->ionAuth->messages());
				return redirect()->to('/admin')->withCookies();
			}
			else
			{
				$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
				//return redirect()->back()->withInput();
				return redirect()->to('/auth/login')->withInput();
			}
		}
		else
		{
			$this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
			$this->data['identity'] = [
				'name'  => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => set_value('identity'),
			];

			$this->data['password'] = [
				'name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			];
			return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'login', $this->data);
		}
	}
}