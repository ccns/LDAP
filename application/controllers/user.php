<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function test()
	{
		$data['status'] = 1;
		echo json_encode($data);
	}
	public function index()
	{
		$this->page();
	}

	public function page($name = NULL)
	{
		$this->load->model('user_model');
		$priv = $this->config->item('privilege');
		$uid = $this->session->userdata('uid');

		$data['user'] = NULL;
		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			if($user){
				$data['user'] = $user[0];
			}

		}

		if($data['user']){

			if(!$name || $name == $this->session->userdata('name')){
				$data['view'] = $user[0];
				$data['local_view'] = 1;
				if($data['user']['priv'] == $priv['admin']){
					$data['allow_add_user'] = 1;
				}
			}else{
				$view = $this->user_model->get_user(array('name'=>$name),NULL);
				if($view){
					$data['view'] = $view[0];
				}
				if($data['user']['priv'] == $priv['admin']){
					$data['allow_edit_user'] = 1;
				}
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

		$data['status'] = 0;

		if(!isset($arg['name']) || !isset($arg['pw'])){
			echo json_encode($data);
			return ;
		}
		$ret = $this->check_name($arg['name']);
		if($ret['status'] == 0){
			$data['msg'] = $ret['msg'];
			echo json_encode($data);
			return;
			
		}
		$arg['name'] = $ret['val'];

		$ret = $this->check_pw($arg['pw']);
		if($ret['status'] == 0){
			$data['msg'] = $ret['msg'];
			echo json_encode($data);
			return;
		}
		$arg['pw'] = do_hash($arg['pw']);

		$user = $this->user_model->get_user(
						array(
							'name'=>$arg['name'],
							'pw'=>$arg['pw']
						),
						NULL
						);
		if($user != FALSE){
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
		
		
		$data['status'] = 0;
		$arg = $this->input->post(NULL,TRUE);

		if(!isset($arg['name']) || !isset($arg['pw']) || !isset($arg['email'])){
			$data['msg'] = 'Your name, password and email are required.';
			echo json_encode($data);
			return ;
		}
		
		$ret = $this->check_name($arg['name']);
		if($ret['status'] == 0){
			$data['msg'] = $ret['msg'];
			echo json_encode($data);
			return ;
		}
		$arg['name'] = $ret['val'];
		
		$ret = $this->check_pw($arg['pw']);
		if($ret['status'] == 0){
			$data['msg'] = $ret['msg'];
			echo json_encode($data);
			return ;
		}
		$arg['pw'] = do_hash($arg['pw']);
				
		$ret = $this->check_email($arg['email']);
		if($ret['status'] == 0){
			$data['msg'] = $ret['msg'];
			echo json_encode($data);
			return ;
		}

		if(isset($arg['realname'])){
			$ret = $this->check_realname($arg['realname']);
			if($ret['status'] == 0){
				$data['msg'] = $ret['msg'];
				echo json_encode($data);
				return ;
			}
			$arg['realname'] = $ret['val'];
		}
		
		if(isset($arg['phone'])){
			$ret = $this->check_phone($arg['phone']);
			if($ret['status'] == 0){
				$data['msg'] = $ret['msg'];
				echo json_encode($data);
				return ;
			}
			$arg['phone'] = $ret['val'];
		}

		if(isset($arg['pages'])){
			$ret = $this->check_pages($arg['pages']);
			if($ret['status'] == 0){
				$data['msg'] = $ret['msg'];
				echo json_encode($data);
				return ;
			}
			$arg['pages'] = $ret['val'];
		}


		if(!isset($arg['priv'])){
			$arg['priv'] = 0;
		}

		$this->user_model->add_user($arg);
		
		$data['status'] = 1;
		echo json_encode($data);
	}
	public function del_user($del_uid = NULL)
	{
		$this->load->model('user_model');
		$priv = $this->config->item('privilege');
		$uid = $this->session->userdata('uid');

		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
		}
		if(!isset($user[0]['priv']) || $user[0]['priv'] != $priv['admin']){
			return ;
		}
		
		if($del_uid && is_numeric($del_uid)){
			$this->user_model->del_user($del_uid);	
		}
	}
	public function edit_user()
	{
		$this->load->model('user_model');
		$this->load->helper('security');
		$priv = $this->config->item('privilege');

		$uid = $this->session->userdata('uid');
		
		$user = NULL;
		$data = NULL;
		$name = NULL;

		if($uid == FALSE){
			$data['status'] = 0;
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return ;
		}	

		$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
		if(!$user){
			$data['status'] = 0;
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return ;
		}

		$arg = $this->input->post(NULL,TRUE);
		if(!$arg){
			$data['status'] = 0;
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return ;
		}
		
		if(!isset($arg['field']) || !isset($arg['val'])){
			$data['status'] = 0;
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return ;
		}
		if($user[0]['priv'] == $priv['admin']){
			$name = $arg['name'];
		}else{
			$name = $user[0]['name'];
		}
		
		$data['status'] = 0;
		$data['field'] = $arg['field'];

		switch($arg['field']){
			case 'realname':
				$ret = $this->check_realname($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return ;
				}
				$arg['val'] = $ret['val'];
				break;
			case 'email':
				$ret = $this->check_email($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return ;
				}
				break;
			case 'phone':
				$ret = $this->check_phone($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return ;
				}
				$arg['val'] = $ret['val'];
				break;
			case 'pages':
				$ret = $this->check_pages($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return ;
				}
				$arg['val'] = $ret['val'];
				break;
			case 'pw':
				$ret = $this->check_pw($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return ;
				}
				$arg['val'] = do_hash($arg['val']);
				break;
			default: 
				$data['status'] = 0;
				$data['msg'] = 'Error. Please try again.';
				$data['field'] = $arg['field'];
				echo json_encode($data);
				return ;
		}
		$ret = $this->user_model->edit_user(array('name'=>$name),array($arg['field']=>$arg['val']));
		if($ret == FALSE){
			$data['status'] = 0;
			$data['msg'] = 'Error. Please try again.';
			$data['field'] = $arg['field'];
			echo json_encode($data);
			return ;
		}

		$data['status'] = 1;
		echo json_encode($data);
	}
	
/* private */
	private function check_name($name = NULL)
	{
		$ret = array('status' => 1);
		if(strlen($name) < 4 || strlen($name) > 16){
			$ret['status'] = 0;
			$ret['msg'] = 'Your name must be more than 4 characterss and less than 16 characters.';
			return $ret;
		}
		$ret['val'] = strtolower($name);
		if(!preg_match("/^[a-z_][a-z0-9_-]*$/",$ret['val'])){
			$ret['status'] = 0;
			$ret['msg'] = 'Your name must match pattern /^[a-z_][a-z0-9_-]*$/.';
			return $ret;
		}
		return $ret;
	}
	private function check_pw($pw = NULL)
	{
		$ret = array('status' => 1);
		if(strlen($pw) < 6 || strlen($pw) > 16){
			$ret['status'] = 0;
			$ret['msg'] = 'Your password must be more than 6 characters and less than 16 characters.';
			return $ret;
		
		}
		if(!preg_match("/^[a-zA-Z0-9+=-_()*&^%$#@!~?><.,\/\|\[\]}{;: ]+$/",$pw)){
			$ret['status'] = 0;
			$ret['msg'] = 'Invalid password.';
			return $ret;
		}
		return $ret;
	}
	private function check_email($email = NULL)
	{
		$ret = array('status' => 1);
		if(strlen($email) > 64){
			$ret['status'] = 0;
			$ret['msg'] = 'Your email must be less than 64 characters.';
			return $ret;

		} 
		if(!preg_match("/^[a-zA-Z0-9_-]*@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/",$email)){
			$ret['status'] = 0;
			$ret['msg'] = 'Invalid email.';
			return $ret;
		}
		return $ret;
	}
	private function check_realname($realname = NULL)
	{
		$ret = array('status' => 1, 'val' => $realname);
		if(strlen($realname) > 16){
			$ret['val'] = substr($realname, 0, 16);
		}
		return $ret;
	}
	private function check_phone($phone = NULL)
	{
		$ret = array('status' => 1, 'val' => $phone);
		if(!preg_match("/^[0-9+-]*$/",$phone)){
			$ret['status'] = 0;
			$ret['msg'] = 'Invalid phone number.';
			return $ret;
		}
		if(strlen($phone) > 20){
			$ret['val'] = substr($phone, 0, 20);
		}
		return $ret;
	}
	private function check_pages($pages = NULL)
	{
		$ret = array('status' => 1, 'val' => $pages);
		if(strlen($pages) > 512){
			$ret['val'] = substr($pages, 0, 512);
		}
		return $ret;
	}
	private function set_page($page,$data)
	{
		$this->load->view('template/header',$data);
		$this->load->view($page);
		$this->load->view('template/side');
		$this->load->view('template/footer');
	}
}
