<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {

	public function __construct() {
		parent::__construct();
		xtoken();
		$this->load->model('apps/roles_model');
		$this->load->model('apps/menu_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->template->load('layout/template', 'application/role/access-menu');
	}

	public function x_access_service()
	{
		_check_ajax_token();
		if ($this->input->post('token') && $this->input->post('token') == 'fetch_result')
		{
			$response = $this->roles_model->getRoleAccessMenu();
			_response_output($response);
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='access_menu')
		{
			$id = $this->input->post('id');
			$check = $this->roles_model->checkRoleById($id);
			if (!$check) {
				$response = [
					'message'   => [
						'title' => 'Gagal',
						'body'  => 'Data tidak tersedia'
					]
				];
				_response_output($response,404);
				return;
			}
			$result = $this->menu_model->getAllMenu($id);
			if (!empty($result)) {
				$response = [
					'result' => $result
				];
				_response_output($response,200);
			} else {
				_response_output(null,204);
			}
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='assign_menu')
		{
			$role = $this->input->post('role',true);
            $menu = $this->input->post('menu',true);

			$data = [
					'id_role' => $role,
					'id_menu' => $menu
				];
			$result = $this->menu_model->assignRoleAccess($data);
			if ($result) {
				$response = [
                    'status'    => TRUE,
                    'message' 	=> [
						'title' => 'Berhasil',
						'body' 	=> 'akses menu diubah'
					]
                ];
                _response_output($response);
			} else {
				_response_output(NULL,500);
			}
		}
	}
}

/* End of file: Access.php */
