<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_directory extends CI_Controller {

	public function index()
	{
		$this->load->model('user_model');
		$uid = $this->session->userdata('uid');

		$data['user'] = NULL;
		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			$data['user'] = $user[0];
		}
		$data['tab']['m_directory'] = 1;
		$this->set_page('m_directory',$data);
	}
	
/* private */
	private function set_page($page,$data){
		$this->load->view('template/header',$data);
		$this->load->view($page);
		$this->load->view('template/side');
		$this->load->view('template/footer');
	}
}
