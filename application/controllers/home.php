<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/home
	 *	- or -  
	 * 		http://example.com/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	private $homefile = 'application/views/home.php';
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
		if(isset($user) && $user[0]['priv'] == $priv['admin']){
			$data['allow_edit_user'] = 1;
		}
		
		if(file_exists($this->homefile)){
			$data['mtime'] = date("Y-m-d",filemtime($this->homefile));
		}

		$data['tab']['home'] = 1;
		$this->set_page('home',$data);
	}
/* private */
	private function set_page($page,$data){
		$this->load->view('template/header',$data);
		$this->load->view($page);
		$this->load->view('template/side');
		$this->load->view('template/footer');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
