<?php  

function is_logged_in(){

	$ci = get_instance();

	if(!$ci->session->userdata('email')){
		redirect('auth');
	}else{
		$roleId = $ci->session->userdata('role_id');
		$menu = $ci->uri->segment(1);

		$querymenu = $ci->db->get_where('user_menu',['menu' => $menu])->row_array();

		$menu_id = $querymenu['id'];

		$userAccess = $ci->db->get_where('user_access_menu',['role_id'=> $roleId, 'menu_id'=>$menu_id]);

		if($userAccess->num_rows() < 1){
			redirect('blocked');
		}
	}
}

function is_logged_off(){
	$ci = get_instance();

	if($ci->session->userdata('role_id')){
		if($ci->session->userdata('role_id') == 1){
			redirect('admin');
			exit();
		}elseif($ci->session->userdata('role_id') == 2){
			redirect('user');
			exit();
		}
		
	}
}


function checked($role_id, $menu_id){
	$ci = get_instance();

	$result = $ci->db->get_where('user_access_menu',['role_id'=>$role_id,'menu_id'=>$menu_id]);

	if($result->num_rows() > 0){
		return "checked = 'checked'";
	}
}


?>