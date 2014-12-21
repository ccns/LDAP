<?php 
class Log_model extends CI_Model {

	var $path = './application/logs/';
	var $filename = 'log'; 
	var $fields = ['timestamp','sname','oname','act','desc'];

	public function add_log($q)
	{
		$this->load->helper('file');

		$q['timestamp'] = (string)time();	

		$data = '';
		foreach ($this->fields as $v){
			$data = $data . (isset($q[$v]) ? $q[$v] : '-') . "\t";
		}	
		$data = preg_replace("/\t$/","\n",$data);

		write_file($this->path.$this->filename,$data,'a');
	}

	public function get_log($type = NULL)
	{
		$this->load->helper('file');
		$data = read_file($this->path.$this->filename);	

		if($type == 'lines'){
			return preg_split("/\n/",$data);		
		}else if($type == 'tokens'){
			$ret = preg_split("/\n/",$data);
			foreach ($ret as &$e){
				$e = preg_split("/\t/",$e);
			}	
			return $ret;
		}

		return $data;
	}
}
?>
