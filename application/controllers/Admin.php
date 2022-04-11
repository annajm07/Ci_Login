<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_logged_in();
	}
	public function index(){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$data['title'] = "Dashboard";
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('admin/index',$data);
		$this->load->view('templates/footer');
	}


	public function role(){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get('user_role')->result_array();
		$data['title'] = "Role";
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('admin/role',$data);
		$this->load->view('templates/footer');
	}


	public function roleaccess($id){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get_where('user_role',['id'=>$id])->row_array();
		
		$this->db->where('id !=', 1);
		$data['menu'] = $this->db->get('user_menu')->result_array();
		$data['title'] = "Role Access";
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('admin/roleaccess',$data);
		$this->load->view('templates/footer');
	}


	public function change_access(){
		$role_id = $this->input->post('role_id');
		$menu_id = $this->input->post('menu_id');

		$result = $this->db->get_where('user_access_menu',['role_id'=>$role_id,'menu_id'=>$menu_id]);

		if($result->num_rows() < 1){
			$this->db->insert('user_access_menu',['role_id'=>$role_id,'menu_id'=>$menu_id]);
		}else{
			$this->db->delete('user_access_menu',['role_id'=>$role_id,'menu_id'=>$menu_id]);
		}
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Access Changed!</div>');
	}
}