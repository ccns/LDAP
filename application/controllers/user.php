<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$this->load->model('user_model');
		$uid = $this->session->userdata('uid');

		$data['user'] = NULL;
		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			$data['user'] = $user[0];
		}
		$data['tab']['user'] = 1;
		$this->set_page('user',$data);
	}
	
	public function sign_in()
	{
		$this->load->model('user_model');
		$this->load->helper('security');
		$arg = $this->input->post(NULL,TRUE);

		if(!isset($arg['name']) || !isset($arg['pw'])){
			$data['status'] = 0;
			echo json_encode($data);
			return ;
		}

		$arg['pw'] = do_hash($arg['pw']);
		$user = $this->user_model->get_user(
						array(
							'name'=>$arg['name'],
							'pw'=>$arg['pw']
						),
						NULL
						);
		if($user == FALSE){
			$data['status'] = 0;
		}else{
			$data['status'] = 1;
			$this->session->set_userdata(
						array(
							'uid' => $user[0]['uid'],
							'name' => $user[0]['name'],
							'email' => $user[0]['email'],
						));
						
		}
		echo json_encode($data); 
	}
	public function sign_out()
	{
		$this->session->sess_destroy();
		echo json_encode(array('status' => 1));
	}
	
/* private */
	private function set_page($page,$data){
		$this->load->view('template/header',$data);
		$this->load->view($page);
		$this->load->view('template/side');
		$this->load->view('template/footer');
	}
}
