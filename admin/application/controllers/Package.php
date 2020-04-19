<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends CI_Controller {
	
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
			'editURL' => $this->my("editURL"),
			'duplicateURL' => $this->my("duplicateURL") 
		);
		$this->load->view('index.php', $param);
	}
	
	public function get(){		
		$result = $this->Modul_Model->read($this->my("getQuery"));

		for($i = 0; $i < count($result); $i++){				
			$result[$i]["3"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' .$result[$i]["key"] . "/" . $result[$i]["key"] . '_on.png?'.date("Ymdhis").'" height="30" />';
			$result[$i]["4"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' .$result[$i]["key"] . "/" . $result[$i]["key"] . '_off.png?'.date("Ymdhis").'" height="30" />';
			$result[$i]["5"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' .$result[$i]["key"] . "/" . $result[$i]["key"] . '_marker.png?'.date("Ymdhis").'" height="30" />';
		}

		echo json_encode($result);
		
	}

	public function add()
	{				
		$param = array(
			'title' => $this->my("title"), 
			'body' => $this->my("formPath"), 
			'tags' => $this->my("tags"), 
			'data' => false,
			'form' => "add", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null
		);
		$this->load->view($param["body"], $param);
	}

	public function edit()
	{		
		$id = $this->input->get('key');
		$data = $this->Modul_Model->read("select * from package where id = '".$id."'");

		if(count($data) > 0){
			$data[0]["ic_on"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' .$data[0]["id"] . "/" . $data[0]["id"] . '_on.png?'.date("Ymdhis").'" height="30" />';
			$data[0]["ic_off"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' .$data[0]["id"] . "/" . $data[0]["id"] . '_off.png?'.date("Ymdhis").'" height="30" />';
			$data[0]["ic_marker"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' .$data[0]["id"] . "/" . $data[0]["id"] . '_marker.png?'.date("Ymdhis").'" height="30" />';
		}
		
		$param = array(
			'key' => $id, 
			'title' => $this->my("title"), 
			'body' => $this->my("formPath"), 
			'tags' => $this->my("tags"), 
			'data' => $data,
			'form' => "edit", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null
		);
		$this->load->view($param["body"], $param);
	}

	public function duplicate(){
		$msg = "Duplikasi data gagal";
		$id = $this->input->post("id");

		$data = $this->Modul_Model->read("select * from package where id = '".$id."'");

		if(count($data) > 0){
			$data = $data[0];
			$data["id"] = date("Ymdhis");
			$data["date_saved"] = $this->api->getFullDateTime();
			$data["user_saved"] = $this->my("userlogin");

			$msg = "Duplikasi Data berhasil";
			$result = $this->Modul_Model->insert($this->my("tableName"), $data);

			if($result){
				$resource_dir = CLIENTICONFILEPATH . 'package/' . $id . '/';
				$target_dir = CLIENTICONFILEPATH . 'package/' . $data["id"] . '/';
			
				if(!is_dir($target_dir)){
					mkdir($target_dir);
				}

				$key = $data["id"];
				$target_file = $target_dir  . $key . '_on.png';
				if(file_exists($resource_dir . $id . '_on.png')){
					copy($resource_dir . $id . '_on.png', $target_file);
				}
				$target_file = $target_dir  . $key . '_off.png';
				if(file_exists($resource_dir . $id . '_off.png')){
					copy($resource_dir . $id . '_off.png', $target_file);
				}
				$target_file = $target_dir  . $key . '_marker.png';
				if(file_exists($resource_dir . $id . '_marker.png')){
					copy($resource_dir . $id . '_marker.png', $target_file);
				}
			}
		}


		echo '[{"status":"1", "msg":"'.$msg.'"}]';
	}
	
	public function post()
	{	
		$data = $this->my("postData");
		
		$key = ""; $msg = "";
		
		if($this->input->post('crud') == "add"){	
			$key = date("Ymdhis");
			$data["id"] = $key;	

			$msg = "Data berhasil disimpan";
			$result = $this->Modul_Model->insert($this->my("tableName"), $data);
		}
		else if($this->input->post('crud') == "edit"){
			$msg = "Data berhasil diubah";
			$key = $this->input->post('key');
			$data["id"] = $key;	
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
			if($this->input->post('crud') != "delete"){		
				$target_dir = CLIENTICONFILEPATH . 'package/' . $data["id"] . '/';
				
				if(!is_dir($target_dir)){
					mkdir($target_dir);
				}
				$target_file = $target_dir  . $key . '_on.png';
				if(isset($_FILES["icon_on"])){
					if (move_uploaded_file($_FILES["icon_on"]["tmp_name"], $target_file)) {}
				}
				$target_file = $target_dir  . $key . '_off.png';
				if(isset($_FILES["icon_off"])){
					if (move_uploaded_file($_FILES["icon_off"]["tmp_name"], $target_file)) {}
				}
				$target_file = $target_dir  . $key . '_marker.png';
				if(isset($_FILES["icon_marker"])){
					if (move_uploaded_file($_FILES["icon_marker"]["tmp_name"], $target_file)) {}
				}
		}

			echo '[{"status":"1", "msg":"'.$msg.'"}]';
		}
		else{
			echo '[{"status":"0", "msg":"Maaf, Proses Gagal. '.$result.'"}]';
		}
	}
	
	public function my($str){
		if($str == "title"){
			return 'Package';
		}
		else if($str == "tag"){
			return 'package';
		}
		else if($str == "bodyPath"){
			return '' . $this->my("tag");
		}
		else if($str == "getQuery"){
			$query = "SELECT id as 'key', tag_id as '1', package_name as '2', nomor_rekening as '3'
					FROM bank where status = 1 order by id";

			$query = "select id as 'key', (select name from tags where id = tag_id) as '1', package_name as '2', icon_on as '3', icon_off as '4', icon_marker as '5', tag_id from package where status = 1 order by id desc";
					
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
		else if($str == "duplicateURL"){
			return base_url().$this->my("tag").'/duplicate';
		}
		else if($str == "tableName"){
			return "package";
		}
		else if($str == "userlogin"){
			return "administrator";
		}
		else if($str == "postData"){
			$data = array(
				'tag_id'			=> $this->input->post('tag_id'),
				'package_name'		=> $this->input->post('package_name'),
				'date_saved'		=> $this->api->getFullDateTime(),
				'user_saved'		=> $this->my("userlogin")
			);
			return $data;
		}
		else if($str == "tags"){
			$query = "select * from tags";
			$result = $this->Modul_Model->read($query);
			return $result;
		}
	}

	function full_copy( $source, $target ) {
	    if ( is_dir( $source ) ) {
	        @mkdir( $target );
	        $d = dir( $source );
	        while ( FALSE !== ( $entry = $d->read() ) ) {
	            if ( $entry == '.' || $entry == '..' ) {
	                continue;
	            }
	            $Entry = $source . '/' . $entry; 
	            if ( is_dir( $Entry ) ) {
	                full_copy( $Entry, $target . '/' . $entry );
	                continue;
	            }
	            copy( $Entry, $target . '/' . $entry );
	        }

	        $d->close();
	    }else {
	        copy( $source, $target );
	    }
	}
}
