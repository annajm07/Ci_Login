<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent::__construct();
		is_logged_in();
	}
	
	public function index(){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$data['title'] = "My Profile";
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('user/index',$data);
		$this->load->view('templates/footer');
	}

	public function edit(){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$data['title'] = "Edit Profile";

		$this->form_validation->set_rules('name','Full Name', 'required|trim');
		if($this->form_validation->run() == false){
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('user/edit',$data);
		$this->load->view('templates/footer');
		}else{

			$new_name = $this->input->post('name');
			$email = $data['user']['email'];
			$old_name = $data['user']['name'];

			// cek image
			$upload_image = $_FILES['image']['name'];
			if($upload_image){
				$config['upload_path'] = './assets/img/profile/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '2048';

				$this->load->library('upload', $config);

				if($this->upload->do_upload('image')){
					$old_image = $data['user']['image'];
					if($old_image != 'default.jpg'){
						unlink(FCPATH.'assets/img/profile/'.$old_image);
					}
					$newImage = $this->upload->data('file_name');
					$this->db->set('image',$newImage);
				}else{
					echo $this->upload->display_errors();
				}

			}
			else{
					$this->db->set('name',$new_name);
				}


			$this->db->where('name',$old_name);
			$this->db->update('user');

			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Your Profile Has been Updated!</div>');
			redirect('user');

		}
	}


	public function change_password(){
		$data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
		$data['title'] = "Change Password";


		$this->form_validation->set_rules('current_password','Current Password','required');
		$this->form_validation->set_rules('new_password','New Password','required|trim|min_length[3]|matches[repeat_password]');
		$this->form_validation->set_rules('repeat_password','Confirm Password','required');

		if($this->form_validation->run() == false){
		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar',$data);
		$this->load->view('templates/topbar',$data);
		$this->load->view('user/change_password',$data);
		$this->load->view('templates/footer');
		}else{
			$current_password = $this->input->post('current_password');
			if(!password_verify($current_password, $data['user']['password'])){
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Wrong Password!</div>');
					redirect('user/change_password');
			}else{
				$new_password = $this->input->post('new_password');
				$new_password = password_hash($new_password, PASSWORD_DEFAULT);

				$this->db->set('password',$new_password);
				$this->db->where('email',$this->session->userdata('email'));
				$this->db->update('user');

				$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Password Changed!</div>');
					redirect('user/change_password');

			}
			
		}
	}
	


}