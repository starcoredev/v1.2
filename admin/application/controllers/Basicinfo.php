<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basicinfo extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
		$this->load->library('API');
		$this->load->library('FileUpload');
	}

	public function index()
	{
		$param = array(
			'title' => $this->my('title'), 
			'body' => $this->my('bodyPath'), 
			'getURL' => $this->my('getURL'), 
			'addURL' => $this->my('addURL'),
			'editURL' => $this->my("editURL") 
		);
		$this->load->view('index.php', $param);
	}
	
	public function get(){		
		$result = $this->Modul_Model->read($this->my("getQuery"));
		echo json_encode($result);
		
	}

	public function add()
	{				
		$param = array(
			'title' => $this->my("title"), 
			'body' => $this->my("formPath"), 
			'data' => false,
			'form' => "add", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null
		);
		$this->load->view('index.php', $param);
	}

	public function edit()
	{		
		$id = $this->input->get('key');
		$data = $this->Modul_Model->read("select * from basic_info where id = '".$id."'");
		
		$param = array(
			'key' => $id, 
			'title' => $this->my("title"), 
			'body' => $this->my("formPath"), 
			'data' => $data,
			'form' => "edit", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null
		);
		$this->load->view('index.php', $param);
	}
	
	public function post()
	{	
		$data = $this->my("postData");
		
		$key = ""; $msg = "";
		
		if($this->input->post('crud') == "add"){	
			$data["id"] = md5($this->api->getFullDateTime());
			$msg = "Data berhasil disimpan";
			$result = $this->Modul_Model->insert($this->my("tableName"), $data);
		}
		else if($this->input->post('crud') == "edit"){
			$msg = "Data berhasil diubah";
			$key = $this->input->post('key');
			$result = $this->Modul_Model->update($this->my("tableName"), $data, "id", $key);
		}
		else if($this->input->post('crud') == "delete"){
			$msg = "Data berhasil dihapus";
			$key = $this->input->post('key');
			$result = $this->Modul_Model->execute("update " . $this->my("tableName") . 
						" set status = 0, date_saved = '".$this->api->getFullDateTime()."', user_saved = '".$this->my("userlogin")."'
						where id = '".$key."' ");
		}
		
		if($result){			
			echo '[{"status":"1", "msg":"'.$msg.'"}]';
		}
		else{
			echo '[{"status":"0", "msg":"Maaf, Proses Gagal. '.$result.'"}]';
		}
	}
	
	public function my($str){
		if($str == "title"){
			return 'Basic Info';
		}
		else if($str == "tag"){
			return 'basic-info';
		}
		else if($str == "bodyPath"){
			return '' . $this->my("tag");
		}
		else if($str == "getQuery"){
			$query = "SELECT id as 'key', name as '1', address as '2', tag as '3'
					FROM basic_info order by date_saved, id";
					
			return $query;
		}
		else if($str == "formPath"){
			return ''.$this->my("tag").'/edit.php';
		}
		else if($str == "addURL"){
			return base_url().$this->my("tag").'/add';
		}
		else if($str == "editURL"){
			return base_url().$this->my("tag").'/edit';
		}
		else if($str == "getURL"){
			return base_url().$this->my("tag").'/get';
		}
		else if($str == "postURL"){
			return base_url().$this->my("tag").'/post';
		}
		else if($str == "tableName"){
			return "basic_info";
		}
		else if($str == "userlogin"){
			return "administrator";
		}
		else if($str == "postData"){
			$data = array(
				'name'				=> $this->input->post('name'),
				'address'			=> $this->input->post('address'),
				'latitude'			=> $this->input->post('latitude'),
				'longitude'			=> $this->input->post('longitude'),
				'tag'				=> $this->input->post('tag'),
				'date_saved'		=> $this->api->getFullDateTime()
			);
			return $data;
		}
		else if($str == "wilayah"){
			$query = "select * from gbei_wilayah order by nama";
			$result = $this->Modul_Model->read($query);
			return $result;
		}
	}
}
