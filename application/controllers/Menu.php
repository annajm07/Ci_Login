<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_logged_in();
	}

	
	public function index(){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$data['menu'] = $this->db->get('user_menu')->result_array();
		$data['title'] = "Menu Management";

		$this->form_validation->set_rules('menu','Menu','required');
		if($this->form_validation->run() == false){
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('menu/index',$data);
		$this->load->view('templates/footer');
		}else{
			$data = ['menu'=> $this->input->post('menu')];
			$this->db->insert('user_menu',$data);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">New Menu added!</div>');
		redirect('menu');

		}
	}


	public function submenu(){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$this->load->model('Menu_model','menu');
		$data['Submenu'] = $this->menu->getSubmenu();
		$data['menu'] = $this->db->get('user_menu')->result_array();
		$data['title'] = "Submenu Management";

		$this->form_validation->set_rules('title','Title','required');
		$this->form_validation->set_rules('menu_id','Menu','required');
		$this->form_validation->set_rules('url','URL','required');
		$this->form_validation->set_rules('icon','Icon','required');

		if($this->form_validation->run() == false){
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('menu/submenu',$data);
		$this->load->view('templates/footer');
		}else{

			if($this->input->post('is_active') == null){
				$is_active = 0;
			}else{
				$is_active = 1;
			}

			$data = [
				  'menu_id' => $this->input->post('menu_id'),
				  'title' => $this->input->post('title'),
				  'icon' => $this->input->post('icon'),
				  'url' => $this->input->post('url'),
				  'is_active' => $is_active
				];

			$this->db->insert('user_sub_menu',$data);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">New Submenu added!</div>');
			redirect('menu/submenu');
		}
	}








}