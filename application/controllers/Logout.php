<?php  
defined('BASEPATH') OR exit('No direct script access allowed');
class Logout extends CI_Controller{

	public function index(){
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">you have been logged out!</div>');
		redirect('auth');
	}
}

?>