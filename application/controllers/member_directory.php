<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_directory extends CI_Controller {

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
			if($data['user']['priv'] == $priv['admin']){
				$data['allow_edit_user'] = 1;
			}
			$user = $this->user_model->get_user(NULL,
								array('where'=>
									array('uid >'=>'1')
								)
							);
			$data['list'] = $user;
		}

		$data['tab']['member_directory'] = 1;
		$this->set_page('member_directory',$data);
	}
	
/* private */
	private function set_page($page,$data){
		$this->load->view('template/header',$data);
		$this->load->view($page);
		$this->load->view('template/side');
		$this->load->view('template/footer');
	}
}
