<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
/* pages */
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
			}else{
				$view = $this->user_model->get_user(array('name'=>$name),NULL);
				if(!$view || $view[0]['uid'] == 1){
					return;
				}
				$view[0] = $this->encode_strings($view[0]);		
				$data['view'] = $view[0];
				if($view[0]['priv'] == $priv['admin']){
					$data['view']['admin_priv'] = 1;
				}else{
					$data['view']['user_priv'] = 1;
				}

				if($view[0]['uid'] > 1 && $data['user']['uid'] <= 1){
					$data['allow_edit_priv'] = 1;
				}
			}
			if($data['user']['priv'] == $priv['admin']){
				$data['allow_edit_user'] = 1;
			}
		}
		
		$data['tab']['user'] = 1;
		$this->set_page('user',$data);

	}
	public function new_user()
	{
		$this->load->model('user_model');
		$priv = $this->config->item('privilege');
		$uid = $this->session->userdata('uid');

		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			if($user){
				$data['user'] = $user[0];
			}
		}
		if(!isset($user[0]['priv']) || $user[0]['priv'] != $priv['admin']){
			return;
		}
		if($data['user']['uid'] <= 1){
			$data['allow_edit_priv'] = 1;
		}
		$data['allow_edit_user'] = 1;
		$data['tab']['new_user'] = 1;
		$this->set_page('new_user',$data);
	}	
	public function registration($hash = NULL)
	{
		$this->load->model('user_model');
		$priv = $this->config->item('privilege');
		$exp = $this->config->item('expiration');
		$uid = $this->session->userdata('uid');

		if(!isset($hash) || strlen($hash) == 0){
			return;
		}
		
		// if user has signed in with another account
		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			if($user){
				$data['user'] = $user[0];
			}
		}

		$ur_user = $this->user_model->get_user(
						array(
							'tmp_pw'=>$hash
						),
						NULL
						);
		if($ur_user == FALSE || !isset($ur_user[0]['email'])){
			return;
		}

		if( $ur_user[0]['priv'] != $priv['unregistered'] || ( isset($exp['registration']) && (time() - (int)$ur_user[0]['pw_timestamp'] > $exp['registration']))){
			return;
		}

		$data['email'] = $ur_user[0]['email'];
		$data['hash'] = $hash;
		$data['invited'] = 1;
		$data['allow_edit_user'] = 1;
		$data['tab']['new_user'] = 1;
		$this->set_page('new_user',$data);
	}
/* operations */
	public function sign_in()
	{
		$this->load->model('user_model');
		$this->load->model('log_model');
		$arg = $this->input->post(NULL,TRUE);

		$data['status'] = 0;

		if(!isset($arg['name']) || !isset($arg['pw'])){
			echo json_encode($data);
			return;
		}
		$ret = $this->check_name($arg['name']);
		if($ret['status'] == 0){
			echo json_encode($data);
			return;
			
		}

		$ret = $this->check_pw($arg['pw']);
		if($ret['status'] == 0){
			echo json_encode($data);
			return;
		}
		$arg['pw'] = hash('sha256', $arg['pw']);


		$user = $this->user_model->get_user(
						array(
							'name'=>$arg['name'],
							'pw'=>$arg['pw']
						),
						NULL
						);

		if($user == FALSE){
			$user = $this->user_model->get_user(
							array(
								'name'=>$arg['name'],
								'tmp_pw'=>$arg['pw']
							),
							NULL
							);
			if($user == FALSE){
				echo json_encode($data);
				return;			
			}
		}

		$ret = $this->check_priv($user[0]['priv']);
		if($ret['status'] == 0){
			echo json_encode($data);
			return;
		}

		$data['status'] = 1;
		$this->session->set_userdata(
					array(
						'uid' => $user[0]['uid'],
						'name' => $user[0]['name'],
						'email' => $user[0]['email'],
					));

		$ret = $this->user_model->edit_user(array('name'=>$user[0]['name']),array('tmp_pw'=>''));
		$this->log_model->add_log(array('sname'=>$user[0]['name'],'act'=>'login'));

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
		$this->load->model('log_model');

		$priv = $this->config->item('privilege');
		$uid = $this->session->userdata('uid');

		$user = NULL;
		$ur_user = NULL;
		$data = NULL;

		$data['status'] = 0;
		$arg = $this->input->post(NULL,TRUE);

		if(isset($arg['h']) && strlen($arg['h']) > 0){
			$user = $this->user_model->get_user(array('tmp_pw'=>$arg['h']),NULL);
			$ur_user = $user;
			if(!isset($user[0]['priv']) || $user[0]['priv'] != $priv['unregistered']){
				$data['msg'] = 'Account already exists.';
				echo json_encode($data);
				return;
			}
		}

		if(!$ur_user && $uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			if(!isset($user[0]['priv']) || $user[0]['priv'] != $priv['admin']){
				$data['msg'] = 'Fail to add user.';
				echo json_encode($data);
				return;
			}
		}
		if(!$user){
			$data['msg'] = 'Fail to add user.';
			echo json_encode($data);
			return;
		}

		if(!isset($arg['name']) || !isset($arg['pw']) || !isset($arg['email'])){
			$data['msg'] = 'Username, password and email are required.';
			echo json_encode($data);
			return;
		}
		
		$ret = $this->check_name($arg['name']);
		if($ret['status'] == 0){
			$data['msg'] = $ret['msg'];
			echo json_encode($data);
			return;
		}
		if($this->user_model->get_user(array('name' => $arg['name']), NULL)){
			$data['msg'] = 'Username already exists.';
			echo json_encode($data);
			return;
		}
		
		$ret = $this->check_pw($arg['pw']);
		if($ret['status'] == 0){
			$data['msg'] = $ret['msg'];
			echo json_encode($data);
			return;
		}
		$arg['pw'] = hash('sha256', $arg['pw']);
				
		if($user[0]['priv'] == $priv['unregistered']){
			// user already has email
			unset($arg['email']);
		}else{
			$ret = $this->check_email($arg['email']);
			if($ret['status'] == 0){
				$data['msg'] = $ret['msg'];
				echo json_encode($data);
				return;
			}
			$ur_user = $this->user_model->get_user(array('email' => $arg['email']), NULL);
			if($ur_user && $ur_user[0]['priv'] != $priv['unregistered']){
				$data['msg'] = 'Email already exists.';
				echo json_encode($data);
				return;
			}
		}

		if(isset($arg['realname'])){
			$ret = $this->check_realname($arg['realname']);
			if($ret['status'] == 0){
				$data['msg'] = $ret['msg'];
				echo json_encode($data);
				return;
			}
		}
		
		if(isset($arg['phone'])){
			$ret = $this->check_phone($arg['phone']);
			if($ret['status'] == 0){
				$data['msg'] = $ret['msg'];
				echo json_encode($data);
				return;
			}
		}

		if(isset($arg['pages'])){
			$ret = $this->check_pages($arg['pages']);
			if($ret['status'] == 0){
				$data['msg'] = $ret['msg'];
				echo json_encode($data);
				return;
			}
		}
		if(isset($ur_user[0]['priv']) && $ur_user[0]['priv'] == $priv['unregistered']){
			$arg['priv'] = $priv['user'];
			$arg['tmp_pw'] = '';
			
			$this->user_model->edit_user(array('uid' => $ur_user[0]['uid']), $arg);
			$this->log_model->add_log(array('sname'=>$arg['name'],'act'=>'register'));
			$data['redirect_index'] = 1;
		}else{
			if(isset($arg['priv'])){
				$ret = $this->check_priv($arg['priv']);
				if($ret['status'] == 0 || !$this->is_root_admin($user[0])){
					$arg['priv'] = $priv['user'];
				}
			}

			$this->user_model->add_user($arg);
			$this->log_model->add_log(array('sname'=>$user[0]['name'],'oname'=>$arg['name'],'act'=>'add user'));
		}
		$this->user_model->update_timestamp(array('name' => $arg['name']));
		
		$data['status'] = 1;
		echo json_encode($data);
	}
	public function del_user($del_uid = NULL)
	{
		$this->load->model('user_model');
		$this->load->model('log_model');
		$priv = $this->config->item('privilege');
		$uid = $this->session->userdata('uid');

		$data['status'] = 0;

		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
		}
		if(!isset($user[0]['priv']) || $user[0]['priv'] != $priv['admin']){
			return;
		}
		$ousr = $this->user_model->get_user(array('uid'=>$del_uid),NULL);
		if($del_uid && is_numeric($del_uid)){
			$ret = $this->user_model->del_user($del_uid);	
			if($ret){
				$data['status'] = 1;
			}
			if($del_uid == $uid){
				$this->session->sess_destroy();
				$data['reload'] = 1;
			}
		}
		if($data['status'] && $ousr){
			$this->log_model->add_log(array('sname'=>$user[0]['name'],'oname'=>$ousr[0]['name'],'act'=>'del user'));
		}

		echo json_encode($data);
	}
	public function edit_user()
	{
		$this->load->model('user_model');
		$this->load->model('log_model');
		$priv = $this->config->item('privilege');

		$uid = $this->session->userdata('uid');
		
		$user = NULL;
		$data = NULL;
		$name = NULL;
		$data['status'] = 0;

		if($uid == FALSE){
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return;
		}	

		$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
		if(!$user){
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return;
		}

		$arg = $this->input->post(NULL,TRUE);
		if(!$arg){
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return;
		}
		
		if(!isset($arg['field']) || !isset($arg['val'])){
			$data['msg'] = 'Error. Please try again.';
			echo json_encode($data);
			return;
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
					return;
				}
				$data['val'] = $arg['val'];
				break;
			case 'email':
				$ret = $this->check_email($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return;
				}
				$same_mail_user = $this->user_model->get_user(array('email' => $arg['val']), NULL);
				if($same_mail_user && $same_mail_user[0]['uid'] != $user[0]['uid']){
					$data['msg'] = 'Email already exists.';
					echo json_encode($data);
					return;
				}
				$data['val'] = $arg['val'];
				break;
			case 'phone':
				$ret = $this->check_phone($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return;
				}
				$data['val'] = $arg['val'];
				break;
			case 'pages':
				$ret = $this->check_pages($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return;
				}
				$data['val'] = $arg['val'];
				break;
			case 'pw':
				$ret = $this->check_pw($arg['val']);
				if($ret['status'] == 0){
					$data['msg'] = $ret['msg'];
					echo json_encode($data);
					return;
				}
				$arg['val'] = hash('sha256', $arg['val']);
				$this->user_model->update_timestamp(array('name' => $name));
				break;
			case 'priv':
				$target = $this->user_model->get_user(array('name'=>$name),NULL);
				if($user[0]['priv'] != $priv['admin'] || $target[0]['uid'] <= 1){
					echo json_encode($data);
					return;
				}
				$arg['val'] = isset($priv[$arg['val']]) ? $arg['val'] = $priv[$arg['val']] : $priv['user']; 
				break;
			default: 
				$data['msg'] = 'Error. Please try again.';
				$data['field'] = $arg['field'];
				echo json_encode($data);
				return;
		}
		$ret = $this->user_model->edit_user(array('name'=>$name),array($arg['field']=>$arg['val']));
		if($ret == FALSE){
			$data['msg'] = 'Error. Please try again.';
			$data['field'] = $arg['field'];
			echo json_encode($data);
			return;
		}
		if(isset($data['val'])){
			$data['val'] = htmlentities($data['val'], ENT_QUOTES);
		}

		$data['status'] = 1;
		$this->log_model->add_log(array('sname'=>$user[0]['name'],'oname'=>$name,'act'=>'edit user','desc'=>'field: '.$arg['field']));

		echo json_encode($data);
	}
	public function forgot_pw()
	{
		
		$this->load->model('user_model');

		$uid = $this->session->userdata('uid');
		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
			if($user){
				header('Location: /index.php/home/');
				return;
			}
		}

		$data['tab']['forgot_pw'] = 1;
		$this->set_page('forgot_pw',$data);
	}
	public function forgot_pw_proc()
	{
	
		$this->load->model('user_model');
		$this->load->model('log_model');
		$this->load->helper('string');
	
		$arg = $this->input->post(NULL,TRUE);

		$data['status'] = 0;

		if(!isset($arg['name']) || !isset($arg['email'])){
			$data['msg'] = 'Wrong user or email.';
			echo json_encode($data);
			return;
		}

		$ret = $this->check_name($arg['name']);
		if($ret['status'] == 0){
			$data['msg'] = 'Wrong user or email.';
			echo json_encode($data);
			return;
		}

		$ret = $this->check_email($arg['email']);
		if($ret['status'] == 0){
			$data['msg'] = 'Wrong user or email.';
			echo json_encode($data);
			return;
		}

		$user = $this->user_model->get_user(array('name'=>$arg['name'],'email'=>$arg['email']),NULL);
		if(!$user){
			$data['msg'] = 'Wrong user or email.';
			echo json_encode($data);
			return;
		}

		$tmp_pw = random_string('alnum',8);
		$tmp_pw_hash = hash('sha256', $tmp_pw);

		$ret = $this->user_model->edit_user(array('name'=>$arg['name']),array('tmp_pw'=>$tmp_pw_hash));

		$name = preg_replace("/\"|\'/","",$user[0]['realname']);

		exec('scripts/forgot_pw.sh "'.$name.'" '.escapeshellarg($arg['email']).' '.$tmp_pw,$res);

		$this->log_model->add_log(array('sname'=>$arg['name'],'act'=>'forgot pw','desc'=>'ip: '.$this->input->ip_address()));

		$data['status'] = 1;
		echo json_encode($data);
	}
	public function invite_user()
	{
		$this->load->model('user_model');
		$this->load->model('log_model');

		$priv = $this->config->item('privilege');
		$exp = $this->config->item('expiration');
		$uid = $this->session->userdata('uid');

		$data['status'] = 0;

		if($uid != FALSE){
			$user = $this->user_model->get_user(array('uid'=>$uid),NULL);
		}
		if(!isset($user[0]['priv']) || $user[0]['priv'] != $priv['admin']){
			echo json_encode($data);
			return;
		}

		$arg['email'] = $this->input->post('email',TRUE);

		if(!isset($arg['email'])){
			$data['msg'] = 'Wrong email.';
			echo json_encode($data);
			return;
		}

		$ret = $this->check_email($arg['email']);
		if($ret['status'] == 0){
			$data['msg'] = 'Wrong email.';
			echo json_encode($data);
			return;
		}

		$ur_user = $this->user_model->get_user(array('email' => $arg['email']), NULL);
		if(isset($ur_user[0]['priv']) && $ur_user[0]['priv'] != $priv['unregistered']){
			$data['msg'] = 'Email already exists.';
			echo json_encode($data);
			return;
		}

		$hash = hash('sha256', $arg['email'] . random_string('unique'));
		$link = site_url('user/registration/'.$hash);
		$arg['name'] = substr(random_string('unique'),0,16); 
		// check unique name 
		$arg['pw'] = $hash;
		$arg['tmp_pw'] = $hash;
		$arg['priv'] = $priv['unregistered']; 
		
		if(isset($ur_user[0]['uid'])){
			$this->user_model->edit_user(array('uid'=>$ur_user[0]['uid']),$arg);
		}else{
			$this->user_model->add_user($arg);
		}
		$this->user_model->update_timestamp(array('name' => $arg['name']));

		exec('scripts/invitation.sh '.escapeshellarg($arg['email']).' '.escapeshellarg($link),$res);
		$this->log_model->add_log(array('sname'=>$user[0]['name'],'act'=>'invite user','desc'=>'email: '.$arg['email']));

		$data['status'] = 1;
		echo json_encode($data);
	} 
/* private */
	private function encode_strings($list = array())
	{
		foreach ($list as &$v){
			$v = htmlentities($v,ENT_QUOTES);
		}
		return $list;
	}
	private function is_root_admin($u)
	{
		$priv = $this->config->item('privilege');
		if(isset($u['priv']) && isset($u['uid']) && $u['priv'] == $priv['admin'] && $u['uid'] == 1){
			
			return true;
		}
		return false;
	}
	private function check_name(&$name = NULL)
	{
		$ret = array('status' => 1);
		if(strlen($name) < 3 || strlen($name) > 16){
			$ret['status'] = 0;
			$ret['msg'] = 'Username must be at least 3 characterss and no longer than 16 characters.';
			return $ret;
		}
		$name = strtolower($name);
		if(!preg_match("/^[a-z_][a-z0-9_-]*$/",$name)){
			$ret['status'] = 0;
			$ret['msg'] = 'Username must match pattern /^[a-z_][a-z0-9_-]*$/.';
			return $ret;
		}
		return $ret;
	}
	private function check_pw($pw = NULL)
	{
		$ret = array('status' => 1);
		if(strlen($pw) < 6 || strlen($pw) > 16){
			$ret['status'] = 0;
			$ret['msg'] = 'Password must be at least 6 characters and no longer than 16 characters.';
			return $ret;
		
		}

		if(!preg_match("/^[a-zA-Z0-9+=-_()*&^%$#@!~?><.,\/\|\[\]}{;: ]+$/",$pw)){
			$ret['status'] = 0;
			$ret['msg'] = 'Invalid password.';
			return $ret;
		}
		return $ret;
	}
	private function check_email(&$email = NULL)
	{
		$ret = array('status' => 1);
		if(strlen($email) < 5){
			$ret['status'] = 0;
			$ret['msg'] = 'Email is required.';
			return $ret;
		}else if(strlen($email) > 64){
			$ret['status'] = 0;
			$ret['msg'] = 'Email must be no longer than 64 characters.';
			return $ret;

		} 
		if(!preg_match("/^([.a-zA-Z0-9_-]+)(@[a-zA-Z0-9_-]+(?:\.[a-zA-Z0-9_-]+))+$/",$email,$match)){
			$ret['status'] = 0;
			$ret['msg'] = 'Invalid email.';
			return $ret;
		}
		$email = $match[1] . (strtolower($match[2]));
		
		return $ret;
	}
	private function check_realname(&$realname = NULL)
	{
		$ret = array('status' => 1, 'val' => $realname);
		if(strlen($realname) > 16){
			$realname = substr($realname, 0, 16);
		}
		return $ret;
	}
	private function check_phone(&$phone = NULL)
	{
		$ret = array('status' => 1, 'val' => $phone);
		if(!preg_match("/^[0-9+-]*$/",$phone)){
			$ret['status'] = 0;
			$ret['msg'] = 'Invalid phone number.';
			return $ret;
		}
		if(strlen($phone) > 20){
			$phone = substr($phone, 0, 20);
		}
		return $ret;
	}
	private function check_pages($pages = NULL)
	{
		$ret = array('status' => 1, 'val' => $pages);
		if(strlen($pages) > 512){
			$pages = substr($pages, 0, 512);
		}
		return $ret;
	}
	private function check_priv($priv = NULL)
	{
		$priv_set = $this->config->item('privilege');
		$ret = array('status' => 0, 'val' => $priv_set['user']);

		if($priv != $priv_set['unregistered']){
			foreach($priv_set as $k=>$v){
				if($priv == $v){
					$ret['status'] = 1;
					$ret['val'] = $priv;
					return $ret;
				}
			}
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
