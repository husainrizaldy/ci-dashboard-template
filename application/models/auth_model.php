<?php
defined('BASEPATH') or exit('No direct script access allowed');

class auth_model extends CI_Model
{
	public function __construct() {
        parent::__construct();
        $this->load->model('tables_model','tables');
    }

    public function getDataUserByEmail($email) {
        return $this->db->select("a.*,b.code,c.fullname,c.picture")
                ->from("{$this->tables->app_users} AS a")
                ->join("{$this->tables->app_roles} AS b","a.roles=b.id","left")
                ->join("{$this->tables->app_users_profile} AS c","a.uid=c.user_id","left")
                ->where("email",$email)
                ->get()->row();
    }
	public function checkUserRoles($role) {
        $query = $this->db->get_where($this->tables->app_roles, ['id' => $role]);
    
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data->status == 1;
        }
        return FALSE;
    }
    public function updateLastLogin($uid) {
        $this->db->where('uid', $uid);
        $this->db->update($this->tables->app_users, array('last_login' => date('Y-m-d H:i:s')));
    }
    
}
