<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		xtoken();
		$this->load->library('form_validation');
		$this->load->model('auth_model');
	}
	public function index()
	{
		if ($this->session->userdata('userSession')) 
		{
			redirect('dashboard');
		}
		$this->load->view('authentication/login');
	}
	public function x_login_action()
	{
		_check_ajax_token();
		if ($this->input->post('token') && $this->input->post('token')=='login') {

			$dt = $this->input->post('datalog');
			parse_str($dt,$data);
			
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('email', 'Email', 
                'trim|required|valid_email',
                array(
                    'required'	=> '%s tidak boleh kosong.'
                ));
			$this->form_validation->set_rules('password', 'Password', 
			'required|trim',
			array(
				'required'	=> '%s tidak boleh kosong.'
			));
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			if ($this->form_validation->run() == false) {
                $response = [
                    'status'   => FALSE,
					'status_error' => 'error-validation',
                    'message'  => array()
                ];
                foreach ($data as $key => $value) {
                    $response['message'][$key] = form_error($key);
                }
                _response_output($response);
				return;
            } else {
				$user = $this->auth_model->getDataUserByEmail($data['email']);
				if ($user) {
					$role_user = $this->auth_model->checkUserRoles($user->roles);
					if ($role_user) {
						if ($user->status == 1) {
							if (password_verify($data['password'], $user->password)) {

								$data_session = [
									'email' 	=> $user->email,
									'user_role' => $user->roles,
									'code_role' => $user->code,
									'user_uid' 	=> $user->uid,
									'user_picture' 	=> $user->picture,
								];
								$this->session->set_userdata('userSession',$data_session);
								$this->session->sess_regenerate(TRUE);
								$this->auth_model->updateLastLogin($user->uid);
								$response = [
									'status'   		=> TRUE,
									'user'  		=> $user->fullname,
									'message'  		=> [
											'title' => 'Berhasil!',
											'body' => 'autentikasi berhasil'
									],
								];
								_response_output($response);
								return;
							} else {
								// wrong password
								$response = [
									'status'   		=> FALSE,
									'status_error' 	=> 'failed',
									'message'  		=> [
										'title' => 'Gagal!',
										'body' => 'email atau password salah'
									]
								];
								_response_output($response);
								return;
							}
						} else {
							// user not active
							$response = [
								'status'   		=> FALSE,
								'status_error' 	=> 'failed',
								'message'  		=> [
										'title' => 'Gagal!',
										'body' => 'email atau password salah'
									]
							];
							_response_output($response);
							return;
						}
					} else {
						$response = [
							'status'   		=> FALSE,
							'status_error' 	=> 'failed',
							'message'  		=> [
									'title' => 'Gagal!',
									'body' => 'email atau password salah'
								]
						];
						_response_output($response);
						return;
					}		
				} else {
					// user not exist
					$response = [
						'status'   		=> FALSE,
						'status_error' 	=> 'failed',
						'message'  		=> [
								'title' => 'Gagal!',
								'body' => 'email atau password salah'
							]
					];
					_response_output($response);
					return;
				}	
			}
		}
	}

	public function logout()
	{
		if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		if ($this->input->get('token') && $this->input->get('token')=='logout') {
			$this->session->sess_destroy(); 
			$response = [
				'status' 	=> TRUE,
				'message' 	=> [
					'title' => 'info',
					'body'	=> 'Berhasil logout'
				]
			];
			_response_output($response);
		}
		
	}
}
