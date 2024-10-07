<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->model('tables_model','tables');
    }

    public function getModulMenu() {
        $data = $this->db
					->where('id !=',1)
					->get($this->tables->app_menu_path)
					->result();
		
		if ($data) {
			foreach ($data as $result) {
				$result->count_content = $this->getContentByIdPath($result->id);
			}
			return $data;
		}
    }
	public function getContentByIdPath($id_path) {
		return $this->db->where('id_path', $id_path)->count_all_results($this->tables->app_menu_content);
	}


	public function getRowModulMenuById($id) {
        return $this->db->where('id',$id)->get($this->tables->app_menu_path)->row();
    }
	public function getContentMenu() {
        $result = $this->db
					->select("
						b.path_name,
						b.path_key,
						a.*
					")
					->from("{$this->tables->app_menu_content} AS a")
					->join("{$this->tables->app_menu_path} AS b","a.id_path=b.id","left")
					->where('a.id !=',1)
					->get()
					->result();
				if ($result) {
					foreach ($result as $results) {
						$results->group_route = $results->menu_route === '#';
					}
					return $result;
				}
    }


	public function getRowContentMenuById($id) {
        $result = $this->db->where('id',$id)->get($this->tables->app_menu_content)->row();
		if ($result) {
			$result->group_status = $result->menu_route === '#';
		}
		return $result;
    }


	public function getGroupContentMenu() {
		return $this->db->where('menu_key IS NOT NULL')
					->get($this->tables->app_menu_content)
					->result();
	}

	public function getSubMenu() {
        $result = $this->db
						->select("
							c.path_key,
							b.menu_name,
							b.menu_key,
							a.*
						")
						->from("{$this->tables->app_menu_sub} AS a")
						->join("{$this->tables->app_menu_content} AS b","a.id_menu=b.id","left")
						->join("{$this->tables->app_menu_path} AS c","b.id_path=c.id","left")
						->where('b.id !=',1)
						->get()
						->result();
		if ($result) {
			foreach ($result as $datas) {
				$datas->full_path = $datas->path_key.'/'.$datas->menu_key.'/'.$datas->sub_route;
			}
		}
		return $result;
    }
	public function getRowSubMenuById($id) {
        return $this->db->where('id',$id)->get($this->tables->app_menu_sub)->row();
    }
    
	/**
	 * role access menu
	 */

	public function getPathMenu(){
		return $this->db
					->where('id !=',1)
					->where('status',1)
					->get($this->tables->app_menu_path)
					->result();
	}
	public function getAllMenu($id_role) {
        $path = $this->getPathMenu();
        $data = [];
        foreach ($path as $result) {
            $row = [
                'path'   => $result->path_name,
                'menu'   => $this->getPrimaryMenu($result->id,$id_role)
            ];
            $data[] = $row;
        }
        return $data;
    }
	public function getPrimaryMenu($id_path,$role) {
        $menu = $this->db
					->select("id,menu_name")
					->get_where($this->tables->app_menu_content, [
							'id_path' => $id_path,
							'status'  => 1
						])->result();
        $data = [];
        foreach ($menu as $result) {
            $row = [
                'id'        => $result->id,
                'name'      => $result->menu_name,
                'checked'   => $this->checkAccessRoleMenu($role,$result->id)
            ];
            $data[] = $row;
        }
        return $data;
    }
	public function checkAccessRoleMenu($role,$menu) {
        $this->db->where('id_role', $role);
        $this->db->where('id_menu', $menu);
        $result = $this->db->get($this->tables->app_roles_access);
    
        if ($result->num_rows() > 0) {
            return "checked";
        } else {
            return "";
        }
    }

	public function assignRoleAccess($data){
        $this->db->trans_start();
        $result = $this->db->get_where($this->tables->app_roles_access, $data);
        if ($result->num_rows() < 1) {
            $this->db->insert($this->tables->app_roles_access,$data);
        } else {
            $this->db->delete($this->tables->app_roles_access,$data);
        }
        $this->db->trans_complete();
		return $this->db->trans_status();
    }

	/**
	 *  callback validation
	 */

	// app_menu_path
	public function _callback_path_name($string, $type = 'default', $id = NULL)
	{
		$allowedTypes = ['add', 'update'];
		if (!in_array($type, $allowedTypes)) {
			$this->form_validation->set_message('validate_path_name', 'Unknow method');
			return FALSE;
		}

		if (empty($string)) {
			$this->form_validation->set_message('validate_path_name', 'Nama modul tidak boleh kosong');
			return FALSE;
		}

		if (strlen($string) < 4) {
			$this->form_validation->set_message('validate_path_name', 'Nama modul harus minimal 4 huruf.');
            return FALSE;
        }

		if (!preg_match('/^[a-zA-Z\s]+$/', $string)) {
			$this->form_validation->set_message('validate_path_name', 'Nama modul hanya boleh mengandung huruf dan spasi.');
			return FALSE;
		}

		$existing = null;
		
		if ($type === 'add') {
			$existing = $this->db->where('path_name', $string)->get($this->tables->app_menu_path)->row();
		} elseif ($type === 'update' && !empty($id)) {
			$existing = $this->db->where('path_name', $string)->where('id !=', $id)->get($this->tables->app_menu_path)->row();
		}

		if ($existing) {
			$this->form_validation->set_message('validate_path_name', 'Nama modul <strong>' . $string . '</strong> sudah tersedia, coba nama lain');
			return FALSE;
		}
		return TRUE;
	}
	public function _callback_path_key($string, $type = 'default', $id = NULL)
	{
		$allowedTypes = ['add', 'update'];
		if (!in_array($type, $allowedTypes)) {
			$this->form_validation->set_message('validate_path_key', 'Unknow method');
			return FALSE;
		}

		if (empty($string)) {
			$this->form_validation->set_message('validate_path_key', 'Nama key tidak boleh kosong');
			return FALSE;
		}

		if (strlen($string) < 4) {
			$this->form_validation->set_message('validate_path_key', 'Nama key harus minimal 4 huruf.');
            return FALSE;
        }

		if (!preg_match('/^[a-zA-Z\-]+$/', $string)) {
			$this->form_validation->set_message('validate_path_key', 'Nama key hanya boleh mengandung huruf dan tanda "-" saja.');
			return FALSE;
		}

		$existing = null;
		
		if ($type === 'add') {
			$existing = $this->db->where('path_key', $string)->get($this->tables->app_menu_path)->row();
		} elseif ($type === 'update' && !empty($id)) {
			$existing = $this->db->where('path_key', $string)->where('id !=', $id)->get($this->tables->app_menu_path)->row();
		}

		if ($existing) {
			$this->form_validation->set_message('validate_path_key', 'Nama key <strong>' . $string . '</strong> sudah tersedia, coba nama lain');
			return FALSE;
		}
		return TRUE;
	}

	// app_menu_content
	public function _callback_menu_name($string, $type = 'default', $id = NULL)
	{
		$allowedTypes = ['add', 'update'];
		if (!in_array($type, $allowedTypes)) {
			$this->form_validation->set_message('validate_menu_name', 'Unknow method');
			return FALSE;
		}

		if (empty($string)) {
			$this->form_validation->set_message('validate_menu_name', 'Nama menu tidak boleh kosong');
			return FALSE;
		}

		if (strlen($string) < 4) {
			$this->form_validation->set_message('validate_menu_name', 'Nama menu harus minimal 4 huruf.');
            return FALSE;
        }

		if (!preg_match('/^[a-zA-Z\s]+$/', $string)) {
			$this->form_validation->set_message('validate_menu_name', 'Nama menu hanya boleh mengandung huruf dan spasi.');
			return FALSE;
		}

		$existing = null;
		
		if ($type === 'add') {
			$existing = $this->db
							->where('id_path', $id)
							->where('menu_name', $string)
							->get($this->tables->app_menu_content)
							->row();
		} elseif ($type === 'update' && !empty($id)) {
			if (is_array($id)) {
				$existing = $this->db
								->where('id_path', $id['id_path'])
								->where('menu_name', $string)
								->where('id !=', $id['id_menu'])
								->get($this->tables->app_menu_content)
								->row();
			}
		}

		if ($existing) {
			$this->form_validation->set_message('validate_menu_name', 'Nama menu <strong>' . $string . '</strong> sudah tersedia, coba nama lain');
			return FALSE;
		}
		return TRUE;
	}

	public function _callback_menu_route($string, $type = 'default', $id = NULL)
	{
		$allowedTypes = ['add', 'update'];
		if (!in_array($type, $allowedTypes)) {
			$this->form_validation->set_message('validate_menu_route', 'Unknow method');
			return FALSE;
		}

		if (empty($string)) {
			$this->form_validation->set_message('validate_menu_route', 'Route menu tidak boleh kosong');
			return FALSE;
		}

		if (strlen($string) < 4) {
			$this->form_validation->set_message('validate_menu_route', 'Route menu harus minimal 4 huruf.');
            return FALSE;
        }

		if (!preg_match('/^[a-zA-Z\-]+$/', $string)) {
			$this->form_validation->set_message('validate_menu_route', 'Route menu hanya boleh mengandung huruf dan tanda "-" saja.');
			return FALSE;
		}

		$existing = null;

		if ($type === 'add') {
			$existing = $this->db
							->where('id_path', $id)
							->where('menu_route', $string)
							->get($this->tables->app_menu_content)
							->row();
		} elseif ($type === 'update' && !empty($id)) {
			if (is_array($id)) {
				$field = '';
				if ($id['status_group']) {
					$field = 'menu_key';
				} else {
					$field = 'menu_route';

				}
				$existing = $this->db
								->where('id_path', $id['id_path'])
								->where($field, $string)
								->where('id !=', $id['id_menu'])
								->get($this->tables->app_menu_content)
								->row();
			}
		}

		if ($existing) {
			$this->form_validation->set_message('validate_menu_route', 'Route menu <strong>' . $string . '</strong> sudah tersedia, coba nama lain');
			return FALSE;
		}
		return TRUE;
	}

	// app_menu_sub
	public function _callback_sub_name($string, $type = 'default', $id = NULL)
	{
		$allowedTypes = ['add', 'update'];
		if (!in_array($type, $allowedTypes)) {
			$this->form_validation->set_message('validate_sub_name', 'Unknow method');
			return FALSE;
		}

		if (empty($string)) {
			$this->form_validation->set_message('validate_sub_name', 'Nama submenu tidak boleh kosong');
			return FALSE;
		}

		if (strlen($string) < 4) {
			$this->form_validation->set_message('validate_sub_name', 'Nama submenu harus minimal 4 huruf.');
            return FALSE;
        }

		if (!preg_match('/^[a-zA-Z\s]+$/', $string)) {
			$this->form_validation->set_message('validate_sub_name', 'Nama submenu hanya boleh mengandung huruf dan spasi.');
			return FALSE;
		}

		$existing = null;
		
		if ($type === 'add') {
			$existing = $this->db
							->where('id_menu', $id)
							->where('sub_name', $string)
							->get($this->tables->app_menu_sub)
							->row();
		} elseif ($type === 'update' && !empty($id)) {
			if (is_array($id)) {
				$existing = $this->db
								->where('id_menu', $id['id_menu'])
								->where('sub_name', $string)
								->where('id !=', $id['id_sub'])
								->get($this->tables->app_menu_sub)
								->row();
			}
		}

		if ($existing) {
			$this->form_validation->set_message('validate_sub_name', 'Nama submenu <strong>' . $string . '</strong> sudah tersedia, coba nama lain');
			return FALSE;
		}
		return TRUE;
	}

	public function _callback_sub_route($string, $type = 'default', $id = NULL)
	{
		$allowedTypes = ['add', 'update'];
		if (!in_array($type, $allowedTypes)) {
			$this->form_validation->set_message('validate_sub_route', 'Unknow method');
			return FALSE;
		}

		if (empty($string)) {
			$this->form_validation->set_message('validate_sub_route', 'Route submenu tidak boleh kosong');
			return FALSE;
		}

		if (strlen($string) < 4) {
			$this->form_validation->set_message('validate_sub_route', 'Route submenu harus minimal 4 huruf.');
            return FALSE;
        }

		if (!preg_match('/^[a-zA-Z\-]+$/', $string)) {
			$this->form_validation->set_message('validate_sub_route', 'Route submenu hanya boleh mengandung huruf dan tanda "-" saja.');
			return FALSE;
		}

		$existing = null;

		if ($type === 'add') {
			$existing = $this->db
							->where('id_menu', $id)
							->where('sub_route', $string)
							->get($this->tables->app_menu_sub)
							->row();
		} elseif ($type === 'update' && !empty($id)) {
			if (is_array($id)) {
				$existing = $this->db
								->where('id_menu', $id['id_menu'])
								->where('sub_route', $string)
								->where('id !=', $id['id_sub'])
								->get($this->tables->app_menu_sub)
								->row();
			}
		}

		if ($existing) {
			$this->form_validation->set_message('validate_sub_route', 'Route submenu <strong>' . $string . '</strong> sudah tersedia, coba nama lain');
			return FALSE;
		}
		return TRUE;
	}
}
