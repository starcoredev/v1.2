<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detailinfo extends CI_Controller {
	
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
			'bname' => $this->my('name'),  
			'body' => $this->my('bodyPath'), 
			'getURL' => $this->my('getURL'), 
			'addURL' => $this->my('addURL'),
			'editURL' => $this->my("editURL"),			
			'basic_id' => $this->input->get("b")
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
			'bname' => $this->my('name'),
			'body' => $this->my("formPath"), 
			'data' => false,
			'form' => "add", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null,			
			'detail_info_tipe' => $this->my("detail_info_tipe"),			
			'basic_id' => $this->input->get("b")
		);
		$this->load->view('index.php', $param);
	}

	public function edit()
	{		
		$id = $this->input->get('key');
		$data = $this->Modul_Model->read("select * from detail_info where id = '".$id."'");
		
		$param = array(
			'key' => $id, 
			'title' => $this->my("title"),   
			'bname' => $this->my('name'),
			'body' => $this->my("formPath"), 
			'data' => $data,
			'form' => "edit", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null,			
			'detail_info_tipe' => $this->my("detail_info_tipe"),		
			'basic_id' => $this->input->get("b")
		);
		$this->load->view('index.php', $param);
	}
	
	public function post()
	{	
		$data = $this->my("postData");
		
		$key = ""; $msg = "";
		
		if($this->input->post('crud') == "add"){	
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
			return 'Detail Info';
		}
		else if($str == "name"){
			$basic_id = $this->input->get("b");
			$name = $this->Modul_Model->read("select name from basic_info where id = '".$basic_id."' limit 0, 1");
			if(count($name) > 0)$name = $name[0]["name"];
			else $name = "";
			return $name;
		}
		else if($str == "tag"){
			return 'detail-info';
		}
		else if($str == "bodyPath"){
			return '' . $this->my("tag");
		}
		else if($str == "getQuery"){			
			$basic_id = $this->input->get("b");
			$query = "SELECT id as 'key', tipe as '1', value as '2'
					FROM detail_info where basic_id = '".$basic_id."'";
					
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
			$basic_id = $this->input->get("b");
			return base_url().$this->my("tag").'/get?b=' . $basic_id;
		}
		else if($str == "postURL"){
			return base_url().$this->my("tag").'/post';
		}
		else if($str == "tableName"){
			return "detail_info";
		}
		else if($str == "userlogin"){
			return "administrator";
		}
		else if($str == "postData"){
			$data = array(
				'basic_id'		=> $this->input->post('basic_id'),
				'tipe'			=> $this->input->post('tipe'),
				'value'			=> $this->input->post('value')
			);
			return $data;
		}
		else if($str == "detail_info_tipe"){
			$query = "select tipe from detail_info group by tipe";
			$result = $this->Modul_Model->read($query);
			return $result;
		}
	}
}
