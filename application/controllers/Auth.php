<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		is_logged_off();
	}

public function index(){
	$data['title'] = "Login Admin";

	$this->form_validation->set_rules('email','Email','trim|required|valid_email');
	$this->form_validation->set_rules('password','Password','trim|required');

if($this->form_validation->run() == false){
	$this->load->view('templates/auth_header',$data);
	$this->load->view('auth/login');
	$this->load->view('templates/auth_footer');
}else{
	$this-> _login();

	}
}

private function _login(){
	$email = $this->input->post('email');
	$password = $this->input->post('password');
	$user = $this->db->get_where('user',['email' => $email])->row_array();

	if($user){
		if($user['is_active'] == 1){
			// cek password
			if(password_verify($password, $user['password'])){
				$data = ['email' => $user['email'],
						 'role_id' => $user['role_id']
						];

				$this->session->set_userdata($data);
				if($user['role_id'] == 1){
				redirect('admin');
				}else{
				redirect('user');
				}
			}else{
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Wrong password!</div>');
				redirect('auth');
			}
		}else{
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Email has not been activated!</div>');
			redirect('auth');
		}
	}else{
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Email is not registered!</div>');
		redirect('auth');
	}

}

public function registration(){
	$data['title'] = "Registration";

	$this->form_validation->set_rules('name','Name','required|trim|is_unique[user.name]',[
		'is_unique' => 'This Name has already registered']);
	$this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[user.email]',[
		'is_unique' => 'This Email has already registered']);
	$this->form_validation->set_rules('password1','Password','required|trim|min_length[3]|matches[password2]',['matches' => 'Password dont match','min_length' => 'Password too short']);
	$this->form_validation->set_rules('password2','Password','required|trim|matches[password1]');

	if($this->form_validation->run() == false){
		$this->load->view('templates/auth_header',$data);
		$this->load->view('auth/registration');
		$this->load->view('templates/auth_footer');
	}else{
		
		$data = ['name' => htmlspecialchars($this->input->post('name',true)),
				 'email' => htmlspecialchars($this->input->post('email',true)),
				 'image' => 'default.jpg',
				 'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				 'role_id' => 2,
				 'is_active' => 0,
				 'date_created' => time() ];

		// bangkitkan bilangan random
		$token = base64_encode(random_bytes(32));
		$user_token = ['email'=>$this->input->post('email'),
					   'token' =>$token,
						'date_created' => time()];

		$this->db->insert('user',$data);
		$this->db->insert('user_token',$user_token);
		$this->_sendEmail($token,'verify');

		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Congratulations! your account has been registered. Please activate your account in 24 hour</div>');
		redirect('auth');
	}
}

private function _sendEmail($token,$type){

		$config = [ 'protocol'=>'smtp',
					'smtp_host'=>'ssl://smtp.googlemail.com',
					'smtp_user'=>'aalbupy@gmail.com',
					'smtp_pass'=>'dispenser7',
					'smtp_port'=>465,
					'mailtype'=>'html',
					'charset'=>'utf-8',
					'newline' =>"\r\n"];

		$this->load->library('email',$config);
		$this->email->initialize($config);




		if($type == 'verify'){
		$this->email->from('aalbupy@gmail.com','Annajm Albupy');
		$this->email->to($this->input->post('email'));
		$this->email->subject('Account Verify');
		$this->email->message('Click this link to verify your Account: <a href= "'.base_url().'auth/verify?email='.$this->input->post('email').'&token='.urlencode($token). '">Active</a>');

		}
		elseif($type == 'forgot'){
			$this->email->from('aalbupy@gmail.com','Annajm Albupy');
			$this->email->to($this->input->post('email'));
			$this->email->subject('Reset Password');
			$this->email->message('Click this link to reset your password: <a href= "'.base_url().'auth/reset?email='.$this->input->post('email').'&token='.urlencode($token). '">Reset Password</a>');
		}


		if($this->email->send()){
			return true;
		}else{
		echo $this->email->print_debugger();
		die();
		}
          
}

public function verify(){
$email = $this->input->get('email');
$token = $this->input->get('token');


$verify = $this->db->get_where('user',['email'=>$email])->row_array();


	if($verify){
		$user_token = $this->db->get_where('user_token',['token'=>$token,'email'=>$email])->row_array();
		if($user_token){
			if(time() - $user_token['date_created'] < (60*60*24)){
				$this->db->set('is_active', 1);
				$this->db->where('email',$email);
				$this->db->update('user');

				$this->db->where('email',$email);
				$this->db->delete('user_token');

				$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">your account has been activated,please login </div>');
			redirect('auth');
			}

		}else{
			$this->db->where('email',$email);
			$this->db->delete('user');

			$this->db->where('email',$email);
			$this->db->delete('user_token');
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">time is up,please register again</div>');
			redirect('auth');
			 }

	}else{
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">failed to activate</div>');
			redirect('auth');
	}

}



public function forgot_password(){
	$data['title'] = "Forgot Password";

	$this->form_validation->set_rules('email','Email','required|trim|valid_email');

	if($this->form_validation->run() == false){
	$this->load->view('templates/auth_header',$data);
	$this->load->view('auth/forgot-password');
	$this->load->view('templates/auth_footer');
	}else{
		$email = $this->input->post('email');
		$user = $this->db->get_where('user',['email'=>$email,'is_active'=>1])->row_array();
		if($user){
		$token = base64_encode(random_bytes(32));
		$user_token = ['email'=>$email,
					   'token' =>$token,
						'date_created' => time()];
		$this->db->insert('user_token',$user_token);

			$this->_sendEmail($token,'forgot');
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">reset password has been sent,please check your email</div>');
			redirect('auth/forgot_password');

		}else{
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">reset password failed! email is not registered or not activated</div>');
			redirect('auth/forgot_password');
		}
	}
}

public function reset(){
$email = $this->input->get('email');
$token = $this->input->get('token');

$reset = $this->db->get_where('user',['email'=>$email])->row_array();

if($reset){
	$user_token = $this->db->get_where('user_token',['token'=>$token])->row_array();
	if($user_token){
		$this->session->set_userdata('reset_email',$email);
		$this->change_password();
	}else{
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">reset password failed! wrong token</div>');
			redirect('auth/forgot_password');
	}
}else{
	$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">reset password failed! wrong email</div>');
			redirect('auth/forgot_password');
}


}

public function change_password(){

	if(!$this->session->userdata('reset_email')){
		redirect('auth');
		exit();
	}

	$data['title'] = "Change Password";

		$this->form_validation->set_rules('password1','Password','required|trim|min_length[3]|matches[password2]');
		$this->form_validation->set_rules('password2','Password','required|trim|min_length[3]|matches[password1]');

		if($this->form_validation->run() == false){
			$this->load->view('templates/auth_header',$data);
			$this->load->view('auth/change_password');
			$this->load->view('templates/auth_footer');
		}else{
			$password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
			$this->db->set('password',$password);
			$this->db->where('email',$this->session->userdata('reset_email'));
			$this->db->update('user');


			$this->db->delete('user_token',['email'=>$this->session->userdata('reset_email')]);
			$this->session->unset_userdata('reset_email');


			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">reset password success! please login</div>');
			redirect('auth');			

		}
}


}
?>