<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		xtoken();
		publicUserAccess();
		$this->load->library('form_validation');
	}
	public function index()
	{
		$this->template->load('layout/template', 'application/dashboard');
	}
	public function x_dashboard_datas() {
		_check_ajax_token();
		
	}
}
