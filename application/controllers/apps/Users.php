<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		xtoken();
		main_access();
		$this->load->model('apps/user_model');
		$this->load->model('apps/roles_model');
		$this->load->model('tables_model','tables');
		$this->load->library('form_validation');
	}
	public function index()
	{
		$this->template->load('layout/template','application/user/data-users');
	}
	public function x_user_service() 
	{
		_check_ajax_token();
		if ($this->input->post('token') && $this->input->post('token')=='fetch_result') 
		{
			$result = $this->user_model->getUserResult();
			_response_output($result);
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='change_status') 
		{
			$uid = $this->input->post('id');
			$status = $this->input->post('status');
            $message = $status == 1 ? 'user di diaktifkan' : 'user di-nonaktifkan';
			
			$rows = ['status' => $status];
			$params = ['uid' => $uid];

			$result = $this->tables->updateRowData($rows, $params, $this->tables->app_users);
			if ($result) {
				$response = [
					'status'    => TRUE,
					'message'   => [
						'title' => 'status',
						'body' => $message
					]
				];
				_response_output($response);
			} else {
				_response_output(NULL,500);
			}
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='get_role') 
		{
			$id = $this->input->post('id');
			$result = $this->roles_model->getUserRoleIgnoreFirst();
			$output = '<option value="">pilih role</option>';
            foreach ($result as $row) {
				if ($id!='999') {
					if ($id == $row->id) {
						$output .= '<option value="'.$row->id.'" selected>'.$row->role_name.'</option>';
					} else {
						$output .= '<option value="'.$row->id.'">'.$row->role_name.'</option>';
					}
				} else {
					$output .= '<option value="'.$row->id.'">'.$row->role_name.'</option>';
				}
            }
			_response_output($output);
		} 
		elseif ($this->input->post('token') && $this->input->post('token')=='add_user') 
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('fullname', 'Nama lengkap', 
				'required|trim',
				array(
					'required'	=> '%s tidak boleh kosong.'
				));
			$this->form_validation->set_rules('email', 'Email', 
				'required|trim|valid_emails|is_unique[app_users.email]',
				array(
					'required'		=> '%s tidak boleh kosong.',
					'valid_emails'	=>'%s tidak valid.',
					'is_unique' 	=> '%s sudah terdaftar, coba dengan email lain.'
				));
			$this->form_validation->set_rules('roles', 'Role', 
				'required|trim',
				array(
					'required'	=> '%s harus dipilih.'
				));
			$this->form_validation->set_error_delimiters('<div class="invalid-tooltip">','</div>');
			if ($this->form_validation->run() == false) {
				$response = [
					'status'   		=> FALSE,
					'error_type' 	=> 'error-validation',
					'message'  		=> array()
				];
				foreach ($data as $key => $value) {
					$response['message'][$key] = form_error($key);
				}
				_response_output($response);
			} else {
				$uid 			= uniqid();
				$password 		= generate_default_password();

				$data_user = [
					'uid'        => $uid,
					'email'      => strtolower($data['email']),
					'password'	 => $password,
					'roles'		 => $data['roles'],
				];

				$data_profile = [
					'user_id'	=> $uid,
					'fullname'	=> trim(strtolower($data['fullname'])), 
					'phone'		=> $data['phone'],
					'address'	=> trim($data['address'])
				];

				$data_settings = [
					'user_id' 	=> $uid
				];

				$result = $this->user_model->addUserData($data_user, $data_profile, $data_settings);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body' => 'User telah ditambahkan'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}

		}
		elseif ($this->input->post('token') && $this->input->post('token')=='get_user') 
		{
			$uid = $this->input->post('data');
			$result = $this->user_model->getUserRow($uid);
			if ($result) {
				$response = [
					'status'   	=> TRUE,
					'result'  	=> $result
				];
				$this->output->set_content_type('application/json')->set_output(json_encode($response));
			} else {
				_response_output(NULL,500);
			}
		}
		
		elseif ($this->input->post('token') && $this->input->post('token')=='update_users') 
		{
			$uid = $this->input->post('i');
			$dt = $this->input->post('d');
			parse_str($dt,$data);

			foreach ($data as $key => $value) {
				$data_key = $key;
				$data_value = $value;
			}

			if (empty($uid)) {
				$response = [
					'message'   	=> [
						'title' => 'Gagal',
						'body' => 'data tidak ditemukan!'
					]
				];
				_response_output($response,500);
				return;
			}

			if ($data_key == 'email') {
				$check = $this->user_model->check_field_email($data_value,$uid);
				if (!$check['status']) {
					$response = [
						'status'    	=> $check['status'],
						'error_type' 	=> 'error-validation',
						'error_field'	=> 'email',
						'message'   	=> [
							'email' => '<div class="invalid-tooltip">'.$check['message'].'</div>'
						]
					];
					_response_output($response);
					return;
				}
			}
			if ($data_key == 'roles') {
				if (empty($data_value)) {
					$response = [
						'status'    	=> false,
						'error_type' 	=> 'error-validation',
						'error_field'	=> 'roles',
						'message'   	=> [
							'roles' => '<div class="invalid-tooltip">role harus dipilih</div>'
						]
					];
					_response_output($response);
					return;
				}
			}

			$field = $data_key;
			$tables = ['app_users', 'app_users_profile'];
			$foundTable = null;
			$params = null;

			foreach ($tables as $table) {
				$query = $this->db->query("SHOW COLUMNS FROM $table LIKE '$field'");
				
				if ($query->num_rows() > 0) {
					$foundTable = $table;
					$params = ($foundTable === 'app_users') ? 'uid' : 'user_id';
					break;
				}
			}
			if ($foundTable == null) {
				$nodata = [
					'message'   	=> [
						'title' => 'Gagal',
						'body' => 'data tidak ditemukan!'
					]
				];
				_response_output($nodata,500);
				return;
			}
			$data_update = [ $data_key => trim($data_value) ];
			$param = [ $params => $uid ];

			$result = $this->tables->updateRowData($data_update, $param, $foundTable);
			if ($result) {
				$response = [
					'status'   	=> TRUE,
					'data_field' => $data_key,
					'invalid_message' => ''
				];
				_response_output($response);
			} else {
				_response_output(NULL,500);
			}
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='change_password')
        {
            $dt = $this->input->post('datalog');
			parse_str($dt,$data);

            $user_uid = $data['uid_users'];
            if (!$user_uid) {
                $response = [
					'message' => [
						'title' => 'gagal',
						'body' => 'pengguna tidak valid'
					]
				];
				_response_output($response);
				return;
            }

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('current_password', 'password saat ini',
				array(
					'required',
					array(
						'current_password_callable',
						function( $str ) use ( $user_uid ){
							return $this->user_model->checkPassword($str, $user_uid); 
						}
					)
				));
			$this->form_validation->set_rules('new_password', 'password baru', 
				'required|min_length[6]|trim|alpha_numeric',
				array(
					'required'	=> 'mohon mengisi %s.',
					'min_length' => 'minimal 6 karakter',
					'alpha_numeric'	=> '%s hanya boleh berisi angka dan huruf.'
				));
			$this->form_validation->set_rules('confirm_password', 'Konfirmasi password baru', 
				'required|min_length[6]|trim|matches[new_password]',
				array(
					'required'	=> 'silahkan %s.',
					'matches' 	=> '%s tidak cocok'
				));
            $this->form_validation->set_error_delimiters('<div class="invalid-tooltip">','</div>');
			if ($this->form_validation->run() == false) {
				$response = [
					'status'   => FALSE,
					'error_type' => 'error-validation',
					'message'  => array()
				];
				foreach ($data as $key => $value) {
					$response['message'][$key] = form_error($key);
				}
				_response_output($response);
			} else {
				$password = password_hash($data['new_password'], PASSWORD_DEFAULT);
				$datas = ['password' => $password ];
				$params = ['uid' => $user_uid];
				$result = $this->tables->updateRowData($datas,$params,$this->tables->app_users);
				if ($result) {
					$response = [
						'status'   => TRUE,
						'message'  => [
							'title' => 'Info Update',
							'body' => 'Berhasil mengganti password'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
        }
		elseif ($this->input->post('token') && $this->input->post('token')=='delete_users')
        {
			$uid = $this->input->post('id');
			$params = ['uid' => $uid];
			$result = $this->tables->deleteRowData($params,$this->tables->app_users);
			if ($result) {
				$response = [
					'status'   	=> TRUE,
					'message' => [
						'title' => 'Info',
						'body' => "Berhasil menghapus data"
					]
				];
				_response_output($response);
			} else {
				_response_output(NULL,500);
			}
		}
	}

}
