<?php 
class User_model extends CI_Model {

	var $table_name = "user";
	var $veiled_fields = array('pw' => 1,'tmp_pw' => 1);
	var $identifiable_fields = array('uid' => 1, 'name' => 1, 'email' => 1);
	var $uneditable_fields = array('uid' => 1, 'pw_timastamp' => 1);

	public function add_user($q)
	{
		$this->load->database();
		
		$f = $this->db->list_fields($this->table_name);
		$data = array();	
		
		foreach ($f as $v){
			if(!isset($this->uneditable_fields[$v])){
				$data[$v] = isset($q[$v]) ? $q[$v] : '';
			}
		}	
		
		return $this->db->insert($this->table_name,$data);
	}
	public function del_user($uid = null)
	{
		$this->load->database();
		if(!isset($uid) || $uid == 1){ return FALSE; }
		$this->db->where('uid',$uid);
		$ret = $this->db->delete($this->table_name);
		if(!$ret){
			return $ret;
		}
		return $this->db->affected_rows();
	}
	public function get_user($q = array(),$con = array())
	{
		$this->load->database();
		$f = $this->db->list_fields($this->table_name);
		$valid = array();
		foreach ($f as $n){
			if(!isset($this->veiled_fields[$n])){
				array_push($valid,$n);
			}
		}
		$this->db->select(join(',',$valid));

		if(isset($con['limit']) && is_numeric($con['limit'])){
			if(!isset($con['offset']) || !is_numeric($con['offset'])){
				$con['offset'] = 0;
			}
			$this->db->limit($con['limit'],$con['offset']);
		}
		if(isset($con['like'])){
			$this->db->like($q);
		}else{
			$this->db->where($q);
		}

		$query = $this->db->get($this->table_name);
		return $query->result_array();
		
	}
	public function edit_user($q = array(),$edit = array())
	{
		$this->load->database();
		$f = $this->db->list_fields($this->table_name);
		$data = array();
		foreach ($f as $v){
			if(isset($edit[$v]) && !isset($this->uneditable_fields[$v])){
				$data[$v] = $edit[$v];
			}
			if(isset($q[$v])){
				$this->db->where($v, $q[$v]);
			}
		}
		return $this->db->update('user', $data);
	}
	public function get_token($name = NULL)
	{
		return false;
	}
	public function update_timestamp($q = array())
	{
		$this->load->database();
		$dt = new DateTime('NOW');
		$data['pw_timestamp'] = (string)$dt->getTimestamp();
		foreach ($this->identifiable_fields as $k=>$v){
			if(isset($q[$k])){
				$this->db->where($k, $q[$k]);
			}
		}
		return $this->db->update('user', $data);
	}
}
?>
