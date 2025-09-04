<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class Users extends BaseController
{
	/**
	 * Configuration
	 *
	 * @var \IonAuth\Config\IonAuth
	 */
	private $configIonAuth;

    public function __construct()
    {
        $this->configIonAuth = config('IonAuth');
        $this->data['currentAdminMenu'] = 'user-role';
        $this->data['currentAdminSubMenu'] = 'user';
    }


	/**
	 * Display informations page
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse|string
	 */
	public function index()
	{
        $this->data['message'] = session()->getFlashdata('message');
        $this->data['users'] =$this->auth->users()->result();
		foreach ($this->data['users'] as $k => $user)
		{
			$this->data['users'][$k]->groups = $this->auth->getUsersGroups($user->id)->getResult();
		}
        return view('admin/users/users', $this->data);
	}
	public function logout()
	{
		// log the user out
		$this->auth->logout();

		// redirect them to the login page
		$this->session->setFlashdata('message', $this->auth->messages());
		return redirect()->to('/auth/login')->withCookies();
	}
	/**
	 * Create a new user
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function createUser()
	{

		$data['title'] = lang('Auth.create_user_heading');
		$tables                 = $this->configIonAuth->tables;
		$identityColumn         = $this->configIonAuth->identity;
		$data['identityColumn'] = $identityColumn;
		$groups        = $this->auth->groups()->resultArray();
		// validate form input
		$validation = \Config\Services::validation();
		$validation->setRule('first_name', lang('Auth.create_user_validation_fname_label'), 'trim|required');
		$validation->setRule('last_name', lang('Auth.create_user_validation_lname_label'), 'trim|required');
		if ($identityColumn !== 'email')
		{
			$validation->setRule(
				'identity',
				lang('Auth.create_user_validation_identity_label'),
				'trim|required|is_unique[' . $tables['users'] . '.' . $identityColumn . ']');
			$validation->setRule(
				'email', lang('Auth.create_user_validation_email_label'), 'trim|required|valid_email');
		}
		else
		{
			$validation->setRule(
				'email',
				lang('Auth.create_user_validation_email_label'),
				'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$validation->setRule('phone', lang('Auth.create_user_validation_phone_label'), 'trim');
		$validation->setRule('company', lang('Auth.create_user_validation_company_label'), 'trim');
		$validation->setRule(
			'password',
			lang('Auth.create_user_validation_password_label'),
			'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[password_confirm]');
		$validation->setRule(
			'password_confirm', lang('Auth.create_user_validation_password_confirm_label'), 'required');

		if ($this->request->getPost() && $validation->withRequest($this->request)->run())
		{
			$email    = strtolower($this->request->getPost('email'));
			$identity = ($identityColumn === 'email') ? $email : $this->request->getPost('identity');
			$password = $this->request->getPost('password');

			$additionalData = [
				'first_name' => $this->request->getPost('first_name'),
				'last_name'  => $this->request->getPost('last_name'),
				'company'    => $this->request->getPost('company'),
				'phone'      => $this->request->getPost('phone'),
			];
			$groupData = $this->request->getPost('groups');
		}
		if (
			$this->request->getPost()
			&& $validation->withRequest($this->request)->run()
			&& $this->auth->register($identity, $password, $email, $additionalData,$groupData)
		)
		{
			// redirect them back to the admin page
			session()->setFlashdata('message', $this->auth->messages());
			return redirect()->to('/admin/users');
		}
		else
		{
			// display the create user form
			helper(['form']);
			// set the flash data error message if there is one
			$data['message'] = $validation->getErrors() ?
									$validation->listErrors() :
									($this->auth->errors() ? $this->auth->errors() : session()->getFlashdata('message'));

			$data['firstName']       = [
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => set_value('first_name'),
				'class'	=> 'form-control',
			];
			$data['lastName']        = [
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => set_value('last_name'),
				'class'	=> 'form-control',
			];
			$data['identity']        = [
				'name'  => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => set_value('identity'),
				'class'	=> 'form-control',
			];
			$data['email']           = [
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'email',
				'value' => set_value('email'),
				'class'	=> 'form-control',
			];
			$data['company']         = [
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => set_value('company'),
				'class'	=> 'form-control',
			];
			$data['phone']           = [
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => set_value('phone'),
				'class'	=> 'form-control',
			];
			$data['password']        = [
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => set_value('password'),
				'class'	=> 'form-control',
			];
			$data['passwordConfirm'] = [
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => set_value('password_confirm'),
				'class'	=> 'form-control',
			];
			$data['groups']        = $groups;
			$data['ionAuth']         = $this->auth;
            $this->data = array_merge($this->data, $data);
            return view('admin/users/create_user', $this->data);
		}
	}

	/**
	 * Edit a user
	 *
	 * @param integer $id User id
	 *
	 * @return string string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function edit(int $id)
	{
		// display the edit user form
		helper(['form']);
		$validation = \Config\Services::validation();
		$data['title'] = lang('Auth.edit_user_heading');
		$user          = $this->auth->user($id)->row();
		$groups        = $this->auth->groups()->resultArray();
		$currentGroups = $this->auth->getUsersGroups($id)->getResult();
		$data['message'] = '';
		if (!empty($_POST))
		{
			// validate form input
			$validation->setRule('first_name', lang('Auth.edit_user_validation_fname_label'), 'trim|required');
			$validation->setRule('last_name', lang('Auth.edit_user_validation_lname_label'), 'trim|required');
			$validation->setRule('phone', lang('Auth.edit_user_validation_phone_label'), 'trim');
			$validation->setRule('company', lang('Auth.edit_user_validation_company_label'), 'trim');

			// do we have a valid request?
			if ($id !== $this->request->getPost('id', FILTER_VALIDATE_INT))
			{
				throw new \Exception(lang('Auth.error_security'));
			}

			// update the password if it was posted
			if ($this->request->getPost('password'))
			{
				$validation->setRule(
					'password',
					lang('Auth.edit_user_validation_password_label'),
					'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[password_confirm]');
				$validation->setRule(
					'password_confirm',
					lang('Auth.edit_user_validation_password_confirm_label'),
					'required');
			}

			if ($this->request->getPost() && $validation->withRequest($this->request)->run())
			{
				$data = [
					'first_name' => $this->request->getPost('first_name'),
					'last_name'  => $this->request->getPost('last_name'),
					'company'    => $this->request->getPost('company'),
					'phone'      => $this->request->getPost('phone'),
				];

				// update the password if it was posted
				if ($this->request->getPost('password'))
				{
					$data['password'] = $this->request->getPost('password');
				}

				// Only allow updating groups if user is admin
				if ($this->auth->isAdmin())
				{
					// Update the groups user belongs to
					$groupData = $this->request->getPost('groups');

					if (! empty($groupData))
					{
						$this->auth->removeFromGroup('', $id);

						foreach ($groupData as $grp)
						{
							$this->auth->addToGroup($grp, $id);
						}
					}
				}

				// check to see if we are updating the user
				if ($this->auth->update($user->id, $data))
				{
					session()->setFlashdata('message', $this->auth->messages());
				}
				else
				{
					session()->setFlashdata('message', $this->auth->errors($validationListTemplate));
				}
				return redirect()->to('/admin/users');
			}
			// set the flash data error message if there is one
			$data['message'] = $validation->getErrors() ? $validation->listErrors() : ($this->auth->errors() ? $this->auth->errors() : $session->getFlashdata('message'));
		}

		// pass the user to the view
		$data['user']          = $user;
		$data['groups']        = $groups;
		$data['currentGroups'] = $currentGroups;

		$data['firstName']       = [
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => set_value('first_name', $user->first_name ?: ''),
			'class'	=> 'form-control',
		];
		$data['lastName']        = [
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => set_value('last_name', $user->last_name ?: ''),
			'class'	=> 'form-control',
		];
		$data['company']         = [
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => set_value('company', empty($user->company) ? '' : $user->company),
			'class'	=> 'form-control',
		];
		$data['phone']           = [
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => set_value('phone', empty($user->phone) ? '' : $user->phone),
			'class'	=> 'form-control',
		];
		$data['password']        = [
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password',
			'class'	=> 'form-control',
		];
		$data['passwordConfirm'] = [
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password',
			'class'	=> 'form-control',
		];
		$data['ionAuth']         = $this->auth;
        $this->data = array_merge($this->data, $data);
        return view('admin/users/edit_user', $this->data);
	}

	/**
	 * Activate the user
	 *
	 * @param integer $id The user ID
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function activate(int $id): \CodeIgniter\HTTP\RedirectResponse
	{
		$this->auth->activate($id);
		session()->setFlashdata('message', $this->auth->messages());
		return redirect()->to('/admin/users');
	}

	/**
	 * Deactivate the user
	 *
	 * @param integer $id The user ID
	 *
	 * @throw Exception
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function deactivate(int $id = 0)
	{

		$validation = \Config\Services::validation();

		$validation->setRule('confirm', lang('Auth.deactivate_validation_confirm_label'), 'required');
		$validation->setRule('id', lang('Auth.deactivate_validation_user_id_label'), 'required|integer');

		if (! $validation->withRequest($this->request)->run())
		{
			helper(['form']);
			$data['user'] = $this->auth->user($id)->row();
            $this->data = array_merge($this->data, $data);
            return view('admin/users/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->request->getPost('confirm') === 'yes')
			{
				// do we have a valid request?
				if ($id !== $this->request->getPost('id', FILTER_VALIDATE_INT))
				{
					throw new \Exception(lang('Auth.error_security'));
				}

				// do we have the right userlevel?
				if ($this->auth->loggedIn() && $this->auth->isAdmin())
				{
					$message = $this->auth->deactivate($id) ? $this->auth->messages() : $this->auth->errors();
					session()->setFlashdata('message', $message);
				}
			}

			// redirect them back to the auth page
			return redirect()->to('/admin/users');
		}
	}

	/**
	 * Edit a group
	 *
	 * @param integer $id Group id
	 *
	 * @return string|CodeIgniter\Http\Response
	 */
	public function editGroup(int $id = 0)
	{
		$validation = \Config\Services::validation();

		$data['title'] = lang('Auth.edit_group_title');

		$group = $this->auth->group($id)->row();

		// validate form input
		$validation->setRule('group_name', lang('Auth.edit_group_validation_name_label'), 'required|alpha_dash');

		if ($this->request->getPost())
		{
			if ($validation->withRequest($this->request)->run())
			{
				$groupUpdate = $this->auth->updateGroup(
									$id,
									$this->request->getPost('group_name'),
									['description' => $this->request->getPost('group_description')]);

				if ($groupUpdate)
				{
					session()->setFlashdata('message', lang('Auth.edit_group_saved'));
				}
				else
				{
					session()->setFlashdata('message', $this->auth->errors());
				}
				return redirect()->to('/admin/users');
			}
		}

		helper(['form']);

		// set the flash data error message if there is one
		$data['message'] = $validation->listErrors() ?: ($this->auth->errors() ?: session()->getFlashdata('message'));

		// pass the user to the view
		$data['group'] = $group;

		$readonly = $this->configIonAuth->adminGroup === $group->name ? 'readonly' : '';

		$data['groupName']        = [
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => set_value('group_name', $group->name),
			$readonly => $readonly,
		];
		$data['groupDescription'] = [
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => set_value('group_description', $group->description),
		];

        $this->data = array_merge($this->data, $data);
        return view('admin/users/edit_group', $this->data);
	}

	/**
	 * Create a new group
	 *
	 * @return string string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function createGroup()
	{

		$data['title'] = lang('Auth.create_group_title');

		$validation = \Config\Services::validation();

		// validate form input
		$validation->setRule('group_name', lang('Auth.create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->request->getPost() && $validation->withRequest($this->request)->run())
		{
			$newGroupId = $this->auth->createGroup($this->request->getPost('group_name'), $this->request->getPost('description'));
			if ($newGroupId)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				session()->setFlashdata('message', $this->auth->messages());
				return redirect()->to('/admin/users');
			}
		}
		else
		{
			// display the create group form
			helper(['form']);
			// set the flash data error message if there is one
			$data['message'] = $validation->getErrors() ? $validation->listErrors() : ($this->auth->errors() ? $this->auth->errors() : session()->getFlashdata('message'));

			$data['groupName']   = [
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => set_value('group_name'),
			];
			$data['description'] = [
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => set_value('description'),
			];

            $this->data = array_merge($this->data, $data);
            return view('admin/users/create_group', $this->data);
		}
	}
}