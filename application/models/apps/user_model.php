<?php
defined('BASEPATH') or exit('No direct script access allowed');

class user_model extends CI_Model
{
	public function __construct() {
        parent::__construct();
        $this->load->model('tables_model','tables');
    }

    public function getUserResult() {
        return $this->db
				->select("
					a.uid,
					a.email,
					a.status,
					a.last_login,
					b.role_name,
					c.fullname,
					c.phone,
					c.address
				")
                ->from("{$this->tables->app_users} AS a")
                ->join("{$this->tables->app_roles} AS b","a.roles=b.id","left")
                ->join("{$this->tables->app_users_profile} AS c","a.uid=c.user_id","left")
                ->where("a.id !=",1)
                ->get()->result();
    }

	public function getUserRow($uid) {
        return $this->db
			->select("a.uid, a.email, a.status, b.fullname, b.phone, b.address, a.roles, c.role_name")
			->from("{$this->tables->app_users} AS a")
			->join("{$this->tables->app_users_profile} AS b", "a.uid = b.user_id")
			->join("{$this->tables->app_roles} AS c", "a.roles = c.id","left")
			->where("a.uid", $uid)
			->get()->row();
    }

	public function addUserData($user,$profile,$settings){
        $this->db->trans_start();
        $this->db->insert($this->tables->app_users, $user);
        $this->db->insert($this->tables->app_users_profile, $profile);
        $this->db->insert($this->tables->app_users_layout_settings, $settings);
        $this->db->trans_complete();
		return $this->db->trans_status();
    }
    
	private function is_valid_email($email) {
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        return preg_match($pattern, $email);
    }
	public function check_field_email($email,$user_id)
    {
        $existing_email = $this->db->where('email', $email)
                                    ->where('uid !=', $user_id)
                                    ->get($this->tables->app_users)
                                    ->row();
        if (empty($email)) {
            return [
                'status' => FALSE,
                'message' => 'Email tidak boleh kosong.'
            ];
        }
        else if (!$this->is_valid_email($email)) {
            return [
                'status' => FALSE,
                'message' => 'Format email tidak valid.'
            ];
        }
        else if ($existing_email) {
            return [
                'status' => FALSE,
                'message' => 'Email sudah digunakan oleh pengguna lain.'
            ];
        }
        else {
            return [
                'status' => TRUE
            ];
        }

    }

	function checkPassword($checkpassword,$uid) {
        $inputpassword = trim($checkpassword);
        if ($inputpassword == '') {
            $this->form_validation->set_message('current_password_callable', 'mohon mengisi {field}');
            return FALSE;
        }
        $userpass = $this->db->select('password')->get_where($this->tables->app_users, ['uid' => $uid])->row();
        if (!password_verify($inputpassword, $userpass->password)) {
            $this->form_validation->set_message('current_password_callable', '{field} tidak cocok');
            return FALSE;
        }
        return TRUE;
    }
}
