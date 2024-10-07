<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		xtoken();
		main_access();
		$this->load->model('apps/roles_model');
		$this->load->library('form_validation');
	}

    public function index()
    {
        $this->template->load('layout/template','application/role/data-roles');
    }

    public function x_roles_service()
    {
        _check_ajax_token();
        if ($this->input->post('token') && $this->input->post('token')=='fetch_result') 
		{
			$response = $this->roles_model->getRolesResult();
			_response_output($response);
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='change_status') 
		{
			$uid = $this->input->post('id');
			$status = $this->input->post('status');
            $message = $status == 1 ? 'role di diaktifkan' : 'role di-nonaktifkan';
			
			$rows = ['status' => $status];
			$params = ['uid' => $uid];

			$result = $this->tables->updateRowData($rows, $params, $this->tables->app_roles);
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
		elseif ($this->input->post('token') && $this->input->post('token')=='add_roles') 
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('role_name', 'Nama role', 
				'required|trim|min_length[4]|regex_match[/^[a-zA-Z\s]+$/]|is_unique[app_roles.role_name]',
				array(
					'required' => '%s tidak boleh kosong.',
					'min_length' => '%s harus minimal 4 huruf.',
					'regex_match' => '%s hanya boleh mengandung huruf dan spasi.',
					'is_unique' => '%s sudah terdaftar, coba dengan nama lain.'
				)
			);
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
				$rows = [
					'uid'		=> uniqid(),
					'role_name'	=> trim($data['role_name']),
					'code'	 	=> convertSpacesToHyphensAndLowercase($data['role_name'])
				];

				$result = $this->tables->addRowData($rows,$this->tables->app_roles);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Role ditambahkan'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}

		}
		elseif ($this->input->post('token') && $this->input->post('token')=='get_roles')
		{
			$uid = $this->input->post('id');
			$result = $this->roles_model->getRolesRows($uid);
			$response = [
				'status'   	=> TRUE,
				'result'  	=> $result
			];
			_response_output($response);
		}
		
		elseif ($this->input->post('token') && $this->input->post('token')=='update_roles') 
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$uid = $data['uid_roles'];
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('role_name', 'Nama role', 
				'required|trim|min_length[4]|regex_match[/^[a-zA-Z\s]+$/]|is_unique[app_roles.role_name.uid.'.$uid.']',
				array(
					'required' => '%s tidak boleh kosong.',
					'min_length' => '%s harus minimal 4 huruf.',
					'regex_match' => '%s hanya boleh mengandung huruf dan spasi.',
					'is_unique' => '%s sudah terdaftar, coba dengan nama lain.'
				)
			);
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
				$rows = [
					'role_name'	=> trim($data['role_name']),
					'code'	 	=> convertSpacesToHyphensAndLowercase($data['role_name'])
				];
				$params = ['uid' => $uid];

				$result = $this->tables->updateRowData($rows,$params,$this->tables->app_roles);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Role diperbahrui'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
		}
		
		elseif ($this->input->post('token') && $this->input->post('token')=='delete_roles')
        {
			$uid = $this->input->post('id');
			$params = ['uid' => $uid];
			$result = $this->tables->deleteRowData($params,$this->tables->app_roles);
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

/* End of file: Roles.php */
