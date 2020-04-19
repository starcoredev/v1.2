<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {
	
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
			'detailURL' => $this->my("detailURL") 
		);
		$this->load->view('index.php', $param);
	}
	
	public function get(){		
		$result = $this->Modul_Model->read($this->my("getQuery"));
		echo json_encode($result);		
	}
	
	public function getData($client, $type){
		$query = "select id as 'key', (select name from tags where id = tag_id) as '1', package_id, tag_id from icon where client_id = '".$client."' and status = 1";
		if($type=="user"){
			$query = "select id as 'key', username as '1',  email as '2', tag_filter as '3', if(active = 1, 'Active', 'Non Active') as '4' from users where client_id = '".$client."' and status = 1";
		}
		else if($type=="package"){
			$query = "select id as 'key', (select name from tags where id = tag_id) as '1', package_name as '2', icon_on as '3', icon_off as '4', icon_marker as '5', tag_id from package where client_id = '".$client."' and status = 1";
		}
		
		//echo $query;
		$result = $this->Modul_Model->read($query);
		
		if($type=="user"){
			for($i = 0; $i < count($result); $i++){
				$tag_filter = $this->Modul_Model->read("select * from tags where id in (".($result[$i]["3"]!=""?$result[$i]["3"]:0).")");
				
				$t = "";
				foreach($tag_filter as $tf){
					$t.=$tf["name"] . ",";
				}
				
				$result[$i]["3"] = (strlen($t) > 0?substr($t, 0, strlen($t) - 1):"-");
			}
		}
		else if($type=="icon"){
			for($i = 0; $i < count($result); $i++){
				$package = $this->Modul_Model->read("select * from package where id = '".$result[$i]["package_id"]."' and status = 1");
				$package_name = ''; $icon_on = ''; $icon_off = ''; $icon_marker = '';
				
				if(count($package) > 0){
					$package = $package[0];
					$package_name = $package["package_name"];
					$icon_on = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $package["id"] . '/' . $package["id"] . '_on.png?'.date("Ymdhis").'" height="30" />';
					$icon_off = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $package["id"] . '/' . $package["id"] . '_off.png?'.date("Ymdhis").'" height="30" />';
					$icon_marker = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $package["id"] . '/' . $package["id"] . '_marker.png?'.date("Ymdhis").'" height="30" />';
				}
				
				$result[$i]["2"] = $package_name;
				$result[$i]["3"] = $icon_on;
				$result[$i]["4"] = $icon_off;
				$result[$i]["5"] = $icon_marker;
			}
		}
		else if($type=="package"){
			for($i = 0; $i < count($result); $i++){
				
				$result[$i]["3"] = '<img src="' . CLIENTICONIMAGESRC . 'client/' . $client . '/' . $result[$i]["key"] . '_on.png?'.date("Ymdhis").'" height="30" />';
				$result[$i]["4"] = '<img src="' . CLIENTICONIMAGESRC . 'client/' . $client . '/' . $result[$i]["key"] . '_off.png?'.date("Ymdhis").'" height="30" />';
				$result[$i]["5"] = '<img src="' . CLIENTICONIMAGESRC . 'client/' . $client . '/' . $result[$i]["key"] . '_marker.png?'.date("Ymdhis").'" height="30" />';
			}
		}
		
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
		$this->load->view($param["body"], $param);
	}

	public function edit()
	{		
		$id = $this->input->get('key');
		$data = $this->Modul_Model->read("select * from client where id = '".$id."'");
		
		$param = array(
			'key' => $id, 
			'title' => $this->my("title"), 
			'body' => $this->my("formPath"), 
			'data' => $data,
			'form' => "edit", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null
		);
		$this->load->view($param["body"], $param);
	}

	public function formData($client_id, $type, $add)
	{	
		$id = $this->input->get('key');
		$client = $this->Modul_Model->read("select * from client where id = '".$client_id."'");
		$data = null;
		
		
		$tags = $this->my("tags");
		
		if($add == 'edit'){
			if($type=='icon'){
				$data = $this->Modul_Model->read("select *, ifnull((select ifnull(package_name, '') from package where id = package_id), '') as 'package_name' from icon where id = '".$id."'");
				$data[0]["icon_on"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $data[0]["package_id"] . '/' . $data[0]["package_id"] . '_on.png?'.date("Ymdhis").'" height="30" />';
				$data[0]["icon_off"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $data[0]["package_id"] . '/' . $data[0]["package_id"] . '_off.png?'.date("Ymdhis").'" height="30" />';
				$data[0]["icon_marker"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $data[0]["package_id"] . '/' . $data[0]["package_id"] . '_marker.png?'.date("Ymdhis").'" height="30" />';
			}
			else if($type=='package'){
				$data = $this->Modul_Model->read("select * from package where id = '".$id."'");
			}
			else if($type=='user'){
				//echo json_encode($tags);
				$data = $this->Modul_Model->read("select * from users where id = '".$id."'");
			}
		}
		
		if($type=='user'){
				$tags = $this->Modul_Model->read("select * from tags where id in (select tag_id from icon where client_id = '".$client_id."')");
		}
		
		//echo json_encode($data);
		
		$param = array(
			'title' => $this->my("title"), 
			'body' => $this->my("tag").'/form'.(ucfirst($type)).'.php', 
			'data' => $data,
			'form' => $add, 
			'postURL' => base_url().$this->my("tag").'/postData', 
			'listFiles' => null,
			'client'=>$client,
			'type'=>$type,
			'tags'=>$tags
		);
		$this->load->view($param["body"], $param);
	}

	public function detail()
	{		
		$id = $this->input->get('key');
		$data = $this->Modul_Model->read("select * from client where id = '".$id."'");
		
		$param = array(
			'key' => $id, 
			'title' => $this->my("title"), 
			'body' => $this->my("tag").'/detail.php', 
			'data' => $data,
			'form' => "edit", 
			'postURL' => base_url().$this->my("tag").'/postData', 
			'getData' => base_url().$this->my("tag").'/getData/' . $id, 
			'formData' => base_url().$this->my("tag").'/formData/' . $id, 
			'listFiles' => null
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
	
	public function postData()
	{	
		$data = $this->my("postDataIcon");
		$type = $this->input->post("type");
		$tableName = 'icon';
		if($type == 'user'){
			$data = $this->my("postDataUser");
			$tableName = 'users';
		}
		else if($type == 'package'){
			$data = $this->my("postDataPackage");
			$tableName = 'package';
		}
		
		$key = ""; $msg = "";
		
		if($this->input->post('crud') == "add"){
			if($type == 'package'){
				$key = date("Ymdhis");
				$data["id"] = $key;
			}
			else if($type == 'icon'){
				$exist = $this->Modul_Model->read("select * from `icon` where client_id  = '".$data["client_id"]."' and tag_id = '".$data["tag_id"]."' and status = 1");
				if(count($exist) > 0){
					echo '[{"status":"0", "msg":"Maaf, Simpan data gagal. Tag telah digunakan."}]';
					exit;
				}
			}
			else if($type == 'user'){
				$exist = $this->Modul_Model->read("select * from `users` where email  = '".$data["email"]."' and status = 1");
				
				if(count($exist) > 0){
					echo '[{"status":"0", "msg":"Maaf, Simpan data gagal. Email <i><b>'.$data["email"].'</b></i> telah digunakan."}]';
					exit;
				}
				
				$exist = $this->Modul_Model->read("select max_user, (select count(*) from `users` where client_id = '".$data["client_id"]."' and status = 1) as 'total' from `client` where id  = '".$data["client_id"]."'");
				if($exist[0]["max_user"] <= $exist[0]["total"]){
					echo '[{"status":"0", "msg":"Maaf, Simpan data gagal. Anda sudah mencapai batas maksimal user."}]';
					exit;
				}
			}
			
			
			$msg = "Data berhasil disimpan";
			$result = $this->Modul_Model->insert($tableName, $data);
		}
		else if($this->input->post('crud') == "edit"){
			$msg = "Data berhasil diubah";
			$key = $this->input->post('key');
			$result = $this->Modul_Model->update($tableName, $data, "id", $key);
		}
		else if($this->input->post('crud') == "delete"){
			$msg = "Data berhasil dihapus";
			$key = $this->input->post('key');
			$result = $this->Modul_Model->execute("update " . $tableName . 
						" set status = 0, date_saved = '".$this->api->getFullDateTime()."', user_saved = '".$this->my("userlogin")."'
						where id = '".$key."' ");
		}
		
		if($result){
			if($type == 'package'){
				$target_dir = CLIENTICONFILEPATH . 'client/' . $data["client_id"] . '/';
				
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
	
	public function resetpassword($do = null){
		if($do != null){
			$status = 0; $msg = "";
			$old = $this->input->post("old");
			$new = $this->input->post("new");
			$new2 = $this->input->post("new2");
			
			
			if($old != $this->session->userdata["admin"]["upwd"]){
				$msg = "Maaf, Password lama salah ";
			}			
			else if($new == "" || $new2 == ""){
				$msg = "Maaf, Password baru dan ulangi password baru harus diisi";
			}
			else if($new != $new2){
				$msg = "Maaf, Re-type password tidak valid";
			}
			else if(strlen($new) < 8){
				$msg = "Maaf, Password minimal 8 karakter";
			}
			else{
				$query = "update superadmin set upwd = '".$new."' where username = '".$this->session->userdata["admin"]["username"]."'";
				
				if($this->Modul_Model->execute($query)){					
					$u = $this->session->userdata["admin"]; 
					$u["upwd"] = $new;
					$this->session->set_userdata("admin",$u);
					
					$status = 1;
					$msg = "Reset Password Berhasil";
				}
				else{
					$msg = "Maaf, terjadi kesalahan. Silahkan ulangi beberapa saat lagi.";
				}
			}
			
			
			echo '[{"status":"'.$status.'", "msg":"'.$msg.'"}]';
			exit;
		}
		
		$param = array(
			'title' => $this->my("title"), 
			'body' => $this->my("tag").'/formResetPassword.php', 
			'postURL' => base_url().$this->my("tag").'/resetpassword/do', 
			'listFiles' => null
		);
		$this->load->view('index.php', $param);
	}
	
	public function my($str){
		if($str == "title"){
			return 'Client';
		}
		else if($str == "tag"){
			return 'client';
		}
		else if($str == "bodyPath"){
			return '' . $this->my("tag");
		}
		else if($str == "getQuery"){
			$query = "SELECT id as 'key', name as '1', concat(max_user, ' User') as '2', start_date as '3', end_date as '4', map_title as '5'
					FROM client where status = 1 order by id";
					
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
		else if($str == "detailURL"){
			return base_url().$this->my("tag").'/detail';
		}
		else if($str == "getURL"){
			return base_url().$this->my("tag").'/get';
		}
		else if($str == "postURL"){
			return base_url().$this->my("tag").'/post';
		}
		else if($str == "tableName"){
			return "client";
		}
		else if($str == "userlogin"){
			return "administrator";
		}
		else if($str == "postData"){
			$data = array(
				'name'				=> $this->input->post('name'),
				'max_user'			=> $this->input->post('max_user'),
				'start_date'		=> $this->input->post('start_date'),
				'end_date'			=> $this->input->post('end_date'),
				'map_title'			=> $this->input->post('map_title'),
				'date_saved'		=> $this->api->getFullDateTime(),
				'user_saved'		=> $this->my("userlogin")
			);
			return $data;
		}
		else if($str == "postDataUser"){
			$data = array(
				'client_id'			=> $this->input->post('client_id'),
				'username'			=> $this->input->post('username'),
				'email'				=> $this->input->post('email'),
				'upwd'				=> $this->input->post('pwd'),
				'tag_filter'		=> $this->input->post('tag_filter'),
				'active'			=> ($this->input->post('active')?1:0),
				'date_saved'		=> $this->api->getFullDateTime(),
				'user_saved'		=> $this->my("userlogin")
			);
			return $data;
		}
		else if($str == "postDataIcon"){
			$data = array(
				'client_id'			=> $this->input->post('client_id'),
				'tag_id'			=> $this->input->post('tag_id'),
				'package_id'		=> $this->input->post('package_id'),
				'date_saved'		=> $this->api->getFullDateTime(),
				'user_saved'		=> $this->my("userlogin")
			);
			return $data;
		}
		else if($str == "postDataPackage"){
			$data = array(
				'client_id'			=> $this->input->post('client_id'),
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
}
