<?php
class Modul_Model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	public function read($query){
		$query = $this->db->query($query);

		return $query->result_array();
	}
	
	public function execute($query){
		$query = $this->db->query($query);

		return $query;
	}	
	
	public function insert($table, $data){
		//$this->db->set('date_saved', 'NOW()', FALSE);
		//$this->db->set('date_update', 'NOW()', FALSE);
		if (!$this->db->insert($table, $data)) {
			$this->db->error();
		}
		else{
			return true;
		}
	}
	
	public function update($table, $data, $key, $id){
		$this->db->set($data);
		//$this->db->set('date_update', 'NOW()', FALSE);
		$this->db->where($key, $id);
		if (!$this->db->update($table, $data)) {
			$this->db->error();
		}
		else{
			return true;
		}
	}
	
	public function get_last_item($table, $key) {		
		$this->db->select('ifnull(max('.$key.'), 0) as id');
		$this->db->from($table);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}
?>