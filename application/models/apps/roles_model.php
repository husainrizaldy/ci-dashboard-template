<?php
defined('BASEPATH') or exit('No direct script access allowed');

class roles_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->model('tables_model','tables');
    }

    public function getRolesResult() {
        return $this->db->where('id !=', 1)->get($this->tables->app_roles)->result();
    }
	public function getRolesRows($uid) {
        return $this->db->where('uid', $uid)->get($this->tables->app_roles)->row();
    }
	public function getUserRoleIgnoreFirst() {
        return $this->db->where('id !=',1)->get($this->tables->app_roles)->result();
    }
	public function checkRoleById($id) {
        $get = $this->db->where('id',$id)->get($this->tables->app_roles);
		return $get->num_rows();
    }
	public function getRoleAccessMenu() {
        return $this->db->get($this->tables->app_roles)->result();
    }
    
}
