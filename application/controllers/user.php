<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$this->load->model('user_model');
		$priv = $this->config->item('privilege');
		$uid = $this->session->userdata('uid');

		$data['user'] = NULL;
		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			$data['user'] = $user[0];
		}
		if($data['user']){
			$data['local_view'] = 1;
			if($data['user']['priv'] == $priv['admin']){
				$data['allow_add_user'] = 1;
			}
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
	public function add_user()
	{
		$this->load->model('user_model');
		$this->load->helper('security');

		$priv = $this->config->item('privilege');
		$uid = $this->session->userdata('uid');

		$user = NULL;
		$data = NULL;

		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
		}
		if(!isset($user[0]['priv']) || $user[0]['priv'] != $priv['admin']){
			return ;
		}
		
		
		$arg = $this->input->post(NULL,TRUE);

		if(!isset($arg['name']) || !isset($arg['pw']) || !isset($arg['confirm']) || !isset($arg['email'])){
			$data['status'] = 0;
			$data['msg'] = 'Your name, password and email required.';
			echo json_encode($data);
			return ;
		}
		if(strlen($arg['name']) < 4 || strlen($arg['name']) > 16){
			$data['status'] = 0;
			$data['msg'] = 'Your name need to be more than 4 letters and less than 16 letters.';
			echo json_encode($data);
			return ;
		}
		if(strlen($arg['pw']) < 6 || strlen($arg['pw']) > 16){
			$data['status'] = 0;
			$data['msg'] = 'Your password need to be more than 6 letters and less than 16 letters';
			echo json_encode($data);
			return ;
		}
		
		if(strlen($arg['email']) > 64){
			$data['status'] = 0;
			$data['msg'] = 'Your email need to be less than 64 letters.';
			echo json_encode($data);
			return ;

		} 
		if(!preg_match("/[a-zA-Z0-9_-]*@\w+(\.\w+)+/",$arg['email'])){
			$data['status'] = 0;
			$data['msg'] = 'Invalid email.';
			echo json_encode($data);
			return ;
		}
		$arg['pw'] = do_hash($arg['pw']);
		$arg['confirm'] = do_hash($arg['confirm']);
		if($arg['pw'] != $arg['confirm']){
			$data['status'] = 0;
			$data['msg'] = 'Your password and confirmation password do not match.';
			echo json_encode($data);
			return ;
		}
		if(!isset($arg['priv'])){
			$arg['priv'] = 0;
		}

		$this->user_model->add_user($arg);
		
		$data['status'] = 1;
		echo json_encode($data);
	}
	public function edit_user(){

	}
	
/* private */
	private function set_page($page,$data){
		$this->load->view('template/header',$data);
		$this->load->view($page);
		$this->load->view('template/side');
		$this->load->view('template/footer');
	}
}
