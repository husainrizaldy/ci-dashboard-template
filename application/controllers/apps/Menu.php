<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		xtoken();
		main_access();
		$this->load->model('apps/menu_model');
		$this->load->library('form_validation');
	}
	public function page_modul()
	{
		$this->template->load('layout/template','application/menu/modul-menu');
	}
    public function page_content()
    {
        $this->template->load('layout/template','application/menu/content-menu');
    }
    public function page_sub()
    {
        $this->template->load('layout/template','application/menu/sub-menu');
    }
    public function x_menu_service()
    {
        _check_ajax_token();
		if ($this->input->post('token') && $this->input->post('token') == 'fetch_modul')
		{
			$result = $this->menu_model->getModulMenu();
			_response_output($result);
		}
		else if ($this->input->post('token') && $this->input->post('token') == 'fetch_content')
		{
			$result = $this->menu_model->getContentMenu();
			foreach ($result as $datas) {
				$menuRoute = $datas->group_route ? $datas->menu_key.'/' : $datas->menu_route;
				$routePath = $datas->path_key.'/'.$menuRoute;
				$datas->registered_route = $this->_checkRegisteredRoute($routePath);
				$datas->route_path = $routePath;
			}
			_response_output($result);
		}
		else if ($this->input->post('token') && $this->input->post('token') == 'fetch_sub')
		{
			$result = $this->menu_model->getSubMenu();
			foreach ($result as $datas) {
				$datas->registered_route = $this->_checkRegisteredRoute($datas->full_path);
			}
			_response_output($result);
		}

		
		elseif ($this->input->post('token') && $this->input->post('token')=='get_modul_list') 
		{
			$id = $this->input->post('id');
			$result = $this->menu_model->getModulMenu();
			$output = '<option value="">pilih modul</option>';
            foreach ($result as $row) {
				if ($id!='999') {
					if ($id == $row->id) {
						$output .= '<option value="'.$row->id.'" selected>'.$row->path_name.'</option>';
					} else {
						$output .= '<option value="'.$row->id.'">'.$row->path_name.'</option>';
					}
				} else {
					$output .= '<option value="'.$row->id.'">'.$row->path_name.'</option>';
				}
            }
			_response_output($output);
		} 

		elseif ($this->input->post('token') && $this->input->post('token')=='get_modul_menu_list') 
		{
			$id = $this->input->post('id');
			$result = $this->menu_model->getGroupContentMenu();
			$output = '<option value="">pilih modul</option>';
            foreach ($result as $row) {
				if ($id!='999') {
					if ($id == $row->id) {
						$output .= '<option value="'.$row->id.'" selected>'.$row->menu_name.'</option>';
					} else {
						$output .= '<option value="'.$row->id.'">'.$row->menu_name.'</option>';
					}
				} else {
					$output .= '<option value="'.$row->id.'">'.$row->menu_name.'</option>';
				}
            }
			_response_output($output);
		} 

		
		elseif ($this->input->post('token') && $this->input->post('token')=='change_status') 
		{
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			$cat = $this->input->post('cat');
            if (empty($cat)) {
				$response = [
					'message' => [
						'title' => 'Info',
						'body' => 'Unknow category'
					]
				];
				_response_output($response,500);
				return;
			}
			
			$tb = '';
			switch ($cat) {
				case 'path':
					$tb = $this->tables->app_menu_path;
					break;
				case 'content':
					$tb = $this->tables->app_menu_content;
					break;
				case 'sub':
					$tb = $this->tables->app_menu_sub;
					break;
				default:
					_response_output(NULL,500);
			}
			
			$message = $status == 1 ? 'role di diaktifkan' : 'role di-nonaktifkan';
			
			$rows = ['status' => $status];
			$params = ['id' => $id];

			$result = $this->tables->updateRowData($rows, $params, $tb);
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
		elseif ($this->input->post('token') && $this->input->post('token')=='delete_menu')
        {
			$id = $this->input->post('id');
			$cat = $this->input->post('cat');
            if (empty($cat)) {
				$response = [
					'message' => [
						'title' => 'Info',
						'body' => 'Unknow category'
					]
				];
				_response_output($response,500);
				return;
			}
			
			$tb = '';
			switch ($cat) {
				case 'path':
					$tb = $this->tables->app_menu_path;
					break;
				case 'content':
					$tb = $this->tables->app_menu_content;
					break;
				case 'sub':
					$tb = $this->tables->app_menu_sub;
					break;
				default:
					_response_output(NULL,500);
			}
			$params = ['id' => $id];
			$result = $this->tables->deleteRowData($params,$tb);
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

		elseif ($this->input->post('token') && $this->input->post('token')=='add_path') 
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('path_name', 'Nama modul', 
                [['validate_path_name',
					function($str) {
						return $this->menu_model->_callback_path_name($str,'add',null);
					}
				]]
            );
			$this->form_validation->set_rules('path_key', 'key modul', 
                [['validate_path_key',
					function($str) {
						return $this->menu_model->_callback_path_key($str,'add',null);
					}
				]]
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
					'path_name'	=> trim($data['path_name']),
					'path_key'	=> trim(strtolower($data['path_key'])),
					'data_key'	=> convertToFormattedStringWithPrefix($data['path_name']),
					'data_icon'	=> 'bx bx-grid-alt'
				];

				$result = $this->tables->addRowData($rows,$this->tables->app_menu_path);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Modul ditambahkan'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}

		}
		elseif ($this->input->post('token') && $this->input->post('token')=='get_path')
		{
			$id = $this->input->post('id');
			$result = $this->menu_model->getRowModulMenuById($id);
			$response = [
				'status'   	=> TRUE,
				'result'  	=> $result
			];
			_response_output($response);
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='update_path') 
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$id_path = $data['id_path'];
			$this->form_validation->set_data($data);

			$this->form_validation->set_rules('path_name', 'Nama modul', 
                [['validate_path_name',
					function($str) use ($id_path) {
						return $this->menu_model->_callback_path_name($str,'update',$id_path);
					}
				]]
            );
			$this->form_validation->set_rules('path_key', 'key modul', 
                [['validate_path_key',
					function($str) use ($id_path){
						return $this->menu_model->_callback_path_key($str,'update',$id_path);
					}
				]]
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
					'path_name'	=> trim($data['path_name']),
					'path_key'	=> trim(strtolower($data['path_key'])),
					'data_key'	=> convertToFormattedStringWithPrefix($data['path_name']),
					'data_icon'	=> 'bx bx-grid-alt'
				];
				$params = ['id' => $id_path];

				$result = $this->tables->updateRowData($rows,$params,$this->tables->app_menu_path);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Modul diperbahrui'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
		}

		elseif ($this->input->post('token') && $this->input->post('token')=='add_content')
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$id_path = $data['id_path'];
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('id_path', 'Modul', 
				'required',
				array(
					'required' => '%s harus dipilih.',
				)
			);
			$this->form_validation->set_rules('menu_name', 'Nama menu', 
                [['validate_menu_name',
					function($str) use ($id_path) {
						return $this->menu_model->_callback_menu_name($str,'add',$id_path);
					}
				]]
            );
			$this->form_validation->set_rules('menu_route', 'Route menu', 
                [['validate_menu_route',
					function($str) use ($id_path) {
						return $this->menu_model->_callback_menu_route($str,'add',$id_path);
					}
				]]
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

				$fieldRoute = trim(strtolower($data['menu_route']));
				$menuRoute = '';
				$menuKey = '';
				$groupMenu = $data['group_menu'];
				if ($groupMenu === 'group') {
					$menuRoute = '#';
					$menuKey = $fieldRoute;
				} else if($groupMenu === 'single'){
					$menuRoute = $fieldRoute;
					$menuKey = null;
				}

				$rows = [
					'id_path'	 => $data['id_path'],
					'menu_name'	 => trim($data['menu_name']),
					'menu_route' => $menuRoute,
					'menu_key'   => $menuKey,
					'data_icon'	 => empty($data['data_icon']) ? 'bx bx-grid-alt' : trim($data['data_icon']),
					'data_key'	 => convertToFormattedStringWithPrefix($data['menu_name']),
				];

				$result = $this->tables->addRowData($rows,$this->tables->app_menu_content);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Menu ditambahkan'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='get_content')
		{
			$id = $this->input->post('id');
			$result = $this->menu_model->getRowContentMenuById($id);
			$response = [
				'status'   	=> TRUE,
				'result'  	=> $result
			];
			_response_output($response);
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='update_content')
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$id_menu = $data['id_menu'];
			$id_path = $data['id_path'];
			$status_group = $data['group_menu'] === 'group';

			$data_id = [
				'id_menu' => $id_menu,
				'id_path' => $id_path,
				'status_group' => $status_group,
			];
			
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('id_path', 'Modul', 
				'required',
				array(
					'required' => '%s harus dipilih.',
				)
			);

			$this->form_validation->set_rules('menu_name', 'Nama menu', 
                [['validate_menu_name',
					function($str) use ($data_id) {
						return $this->menu_model->_callback_menu_name($str,'update',$data_id);
					}
				]]
            );
			$this->form_validation->set_rules('menu_route', 'Route menu', 
                [['validate_menu_route',
					function($str) use ($data_id) {
						return $this->menu_model->_callback_menu_route($str,'update',$data_id);
					}
				]]
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

				$fieldRoute = trim(strtolower($data['menu_route']));
				$menuRoute = '';
				$menuKey = '';
				if ($status_group) {
					$menuRoute = '#';
					$menuKey = $fieldRoute;
				} else {
					$menuRoute = $fieldRoute;
					$menuKey = null;
				}
				$rows = [
					'id_path'	 => $data['id_path'],
					'menu_name'	 => trim($data['menu_name']),
					'menu_route' => $menuRoute,
					'menu_key'   => $menuKey,
					'data_icon'	 => empty($data['data_icon']) ? 'bx bx-grid-alt' : trim($data['data_icon']),
					'data_key'	 => convertToFormattedStringWithPrefix($data['menu_name']),
				];
				$params = ['id' => $id_menu];

				$result = $this->tables->updateRowData($rows,$params,$this->tables->app_menu_content);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Menu diperbahrui'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
		}


		elseif ($this->input->post('token') && $this->input->post('token')=='add_sub')
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$id_menu = $data['id_menu'];
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('id_menu', 'Modul menu', 
				'required',
				array(
					'required' => '%s harus dipilih.',
				)
			);
			$this->form_validation->set_rules('sub_name', 'Nama submenu', 
                [['validate_sub_name',
					function($str) use ($id_menu) {
						return $this->menu_model->_callback_sub_name($str,'add',$id_menu);
					}
				]]
            );
			$this->form_validation->set_rules('sub_route', 'Route submenu', 
                [['validate_sub_route',
					function($str) use ($id_menu) {
						return $this->menu_model->_callback_sub_route($str,'add',$id_menu);
					}
				]]
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
					'id_menu'	=> $data['id_menu'],
					'sub_name'	=> trim($data['sub_name']),
					'sub_route'	=> trim($data['sub_route']),
					'data_key'	=> convertToFormattedStringWithPrefix($data['sub_name']),
					'data_icon'	=> 'bx bx-grid-alt'
				];

				$result = $this->tables->addRowData($rows,$this->tables->app_menu_sub);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Subenu ditambahkan'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='get_sub')
		{
			$id = $this->input->post('id');
			$result = $this->menu_model->getRowSubMenuById($id);
			$response = [
				'status'   	=> TRUE,
				'result'  	=> $result
			];
			_response_output($response);
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='update_sub')
		{
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

			$id_menu = $data['id_menu'];
			$id_sub = $data['id_sub'];
			$data_id = [
				'id_sub' => $id_sub,
				'id_menu' => $id_menu
			];
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('id_menu', 'Modul menu', 
				'required',
				array(
					'required' => '%s harus dipilih.',
				)
			);
			$this->form_validation->set_rules('sub_name', 'Nama submenu', 
                [['validate_sub_name',
					function($str) use ($data_id) {
						return $this->menu_model->_callback_sub_name($str,'update',$data_id);
					}
				]]
            );
			$this->form_validation->set_rules('sub_route', 'Route submenu', 
                [['validate_sub_route',
					function($str) use ($data_id) {
						return $this->menu_model->_callback_sub_route($str,'update',$data_id);
					}
				]]
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
					'id_menu'	=> $data['id_menu'],
					'sub_name'	=> trim($data['sub_name']),
					'sub_route'	=> trim($data['sub_route']),
					'data_key'	=> convertToFormattedStringWithPrefix($data['sub_name'])
				];

				$params = ['id' => $id_sub];

				$result = $this->tables->updateRowData($rows,$params,$this->tables->app_menu_sub);
				if ($result) {
					$response = [
						'status'    => TRUE,
						'message'   => [
							'title' => 'Berhasil',
							'body'  => 'Subenu ditambahkan'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
		}
    }

	private function _checkRegisteredRoute($datacek) {
		include(APPPATH . 'config/routes.php');
		// return array_key_exists($datacek, $route);
		foreach (array_keys($route) as $key) {
			if (strpos($key, $datacek) === 0) {
				return true;
			}
		}
		return false;
	}
}
