<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extras extends CI_Controller {
	public function not_found()
	{
		$this->load->view("layout/404-not-found");
	}
}