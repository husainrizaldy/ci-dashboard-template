<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tables_model extends CI_Model 
{
    // table menu
    public $app_menu_path             	= "app_menu_path";
    public $app_menu_content          	= "app_menu_content";
    public $app_menu_sub              	= "app_menu_sub";
    
    // table user
    public $app_roles_access          	= "app_roles_access";
    public $app_users                 	= "app_users";
    public $app_users_profile         	= "app_users_profile";
    public $app_roles            	  	= "app_roles";
    public $app_users_layout_settings 	= "app_users_layout_settings";


	/** 
	 * CRUD Function 
	 * @return bool
	 * 
	 * */

	public function addRowData($data,$table){
        if (empty($table)) {return false;}
        $this->db->trans_start();
        $this->db->insert($table, $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function addBatchData($data,$table){
        if (empty($table)) {return false;}
        $this->db->trans_start();
        $this->db->insert_batch($table, $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function updateRowData($data,$param,$table){
        if (empty($table)) {return false;}
        $this->db->trans_start();
        $this->db->update($table, $data, $param);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function deleteRowData($param,$table){
        if (empty($table)) {return false;}
        $this->db->trans_start();
        $this->db->delete($table, $param);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
	/** END of CRUD */
}

/* End of file: Tables_model.php */
