<?php
defined('BASEPATH') or exit('No direct script access allowed');

class profile_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->model('tables_model','tables');
    }

    public function getUsersProfile() 
    {
        $user_uid = currentDataUserSession('uid');
        if (!$user_uid) {
            return ['status' => false, 'data' => []];
        }

        $result = $this->db->select('
            a.uid, 
            a.email, 
            b.fullname,
			d.role_name,
            b.phone, 
            b.picture, 
            b.address, 
            c.theme_mode, 
            c.theme_layout, 
            c.sidebar, 
            c.topbar
        ')
		->from("{$this->tables->app_users} AS a")
        ->join("{$this->tables->app_users_profile} AS b", "a.uid = b.user_id","left")
        ->join("{$this->tables->app_users_layout_settings} AS c", "a.uid = c.user_id","left")
        ->join("{$this->tables->app_roles} AS d", "a.roles = d.id","left")
        ->where("a.uid", $user_uid)
        ->get()->row();
		return (object) [
			'status' => true,
			'data' => $result
		];
    }

	/**
	 * validation
	 */
	public function _callback_check_email($email,$user_id)
    {
        if (empty($email)) {
			$this->form_validation->set_message('validate_user_email', 'Email tidak boleh kosong');
			return FALSE;
		}

		if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
			$this->form_validation->set_message('validate_user_email', 'Email tidak valid.');
			return FALSE;
		}

        $existing_email = $this->db->where('email', $email)
                                    ->where('uid !=', $user_id)
                                    ->get($this->tables->app_users)
                                    ->row();

        if ($existing_email) {
            $this->form_validation->set_message('validate_user_email', 'Email sudah digunakan oleh pengguna lain.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
