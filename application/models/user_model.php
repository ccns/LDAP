<?php 
class User_model extends CI_Model {

	var $table_name = "user";
	var $exclude_fields = array('pw' => 1);

	public function add_user($q)
	{
		$this->load->database();
		
		$f = $this->db->list_fields($this->table_name);
		$data = array();	
		
		foreach ($f as $v){
			$data[$v] = isset($q[$v]) ? $q[$v] : '';
		}	
		
		return $this->db->insert($this->table_name,$data);
	}
	public function del_user($uid = null)
	{
		$this->load->database();
		if(!isset($uid) || $uid == 1){ return FALSE; }
		$this->db->where('uid',$uid);
		return $this->db->delete($this->table_name);
	}
	public function get_user($q = array(),$con = array())
	{
		$this->load->database();
		$f = $this->db->list_fields($this->table_name);
		$valid = array();
		$where = array();
		foreach ($f as $n){
			if(!isset($exclude_fields[$n])){
				array_push($valid,$n);
			}
			if(isset($q[$n])){
				$where[$n] = $q[$n];
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
			$this->db->like($where);
		}else{
			$this->db->where($where);
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
			if(isset($edit[$v])){
				$data[$v] = $edit[$v];
			}
			if(isset($q[$v])){
				$this->db->where($v, $q[$v]);
			}
		}
		return $this->db->update('user', $data);
	}
}
?>
