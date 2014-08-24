<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$id = $this->session->userdata('id');
		$data['user'] = NULL;
		if($id != FALSE){
			$user = $this->user_model->get_user(
							array(
								'id'=>$arg['id'],
							),
							NULL
							);
			$data['user'] = $user;
		}
		$this->set_page('user',$data);
	}
	
	public function sign_in()
	{
		$this->load->model('user_model');
		$this->load->helper('security');
		$arg = $this->input->post(NULL,TRUE);
		$arg['pw'] = do_hash($arg['pw']);
		$user = $this->user_model->get_user(
						array(
							'id'=>$arg['id'],
							'pw'=>$arg['pw']
						),
						NULL
						);
		if($user == FALSE){
			$data['status'] = 0;
		}else{
			$data['user'] = $user;	
			$data['status'] = 1;
			$this->session->set_userdata(
						array(
							'id'=>$data['id'],
							'name'=>$data['name'],
							'email'=>$data['email'],
						));
						
		}
		echo json_encode($data); 
	}
	public function sign_out()
	{
		$this->session->sess_destroy();
	}
	
/* private */
	private function set_page($page,$data){
		$this->load->view('template/header',$data);
		$this->load->view($page);
		$this->load->view('template/side');
		$this->load->view('template/footer');
	}
}
