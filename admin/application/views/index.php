<?php

if(isset($_GET["auth"])){
	if($_GET["auth"] == "t"){
		$un = $_GET["un"];
		$u = $this->Modul_Model->read("select * from superadmin where username = '".$un."'");
		if(count($u) > 0){
			$this->session->set_userdata("admin",$u[0]);
			header("location: " . PUBLICURL);
			exit;
		}
		else{
			$this->session->unset_userdata("admin");
			header("location: " . FRONTENDURL);
			exit;
		}
	}
	if($_GET["auth"] == "f"){
		$this->session->unset_userdata("admin");
		header("location: " . FRONTENDURL);
		exit;
	}
}

if(!isset($this->session->userdata["admin"])){
	header("location: " . FRONTENDURL);
	exit;
}

?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap Plugin -->
		<link href="<?php echo base_url() ;?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url() ;?>assets/bootstrap/font-awesome/css/font-awesome.css" rel="stylesheet">

		<script src="<?php echo base_url() ;?>assets/bootstrap/js/jquery.js"></script>
		<script src="<?php echo base_url() ;?>assets/bootstrap/js/jquery.min.js"></script>
		<script src="<?php echo base_url() ;?>assets/bootstrap/js/bootstrap.min.js"></script>

		<!-- datatables -->
		<script type="text/javascript" src="<?php echo base_url();?>assets/datatables/dataTables.bootstrap.min.js" ></script>
		<script type="text/javascript" src="<?php echo base_url();?>assets/datatables/jquery.dataTables.min.js" ></script>
		<link href="<?php echo base_url() ;?>assets/datatables/jquery.dataTables.min.css" rel="stylesheet">
		<!-- // datatables -->

		<!-- scrollbar -->
		<script type="text/javascript" src="<?php echo base_url();?>assets/scrollbar/jquery.scrollbar.js" ></script>
		<link href="<?php echo base_url();?>assets/scrollbar/scrollbar.css?<?php echo date("His"); ?>" rel="stylesheet">
		<!-- // scrollbar -->
		
		
		<link href="<?php echo base_url() ;?>assets/css/styles.css?<?php echo date("His"); ?>" rel="stylesheet">
		<script src="<?php echo base_url() ;?>assets/datatables/datatable.js?<?php echo date("His"); ?>"></script>
		<script src="<?php echo base_url() ;?>assets/js/script.js?<?php echo date("His"); ?>"></script>
		
		<title>Web Admin</title>
	</head>
	<body>
		<?php $this->load->view("templates/header.php"); ?>
		<?php $this->load->view("templates/sidebar.php"); ?>
		
		
		<?php 
			if(strpos($body, '.php') !== false){
				$this->load->view($body);
			}
			else{
				$this->load->view($body . '/index.php');
			}
		?>
		
		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog" style="width:1000px;">
				<!-- Modal content-->
				<div class="modal-content">	
					<button type="button" class="close" data-dismiss="modal">&times;</button>				
					<div class="modal-body">
						<p>Please wait...</p>
					</div>
				</div>

			</div>
		</div>


<!-- Modal -->
		<div class="modal fade" id="myModalxxx" role="dialog">
			<div class="modal-dialog" style="width:1000px;">
				<!-- Modal content-->
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal">&times;</button>		
					<div class="modal-body">
						<p>Please wait...</p>
					</div>
				</div>

			</div>
		</div>
		<style>
		#myModal .modal-content, #myModal .modal-body{
			overflow:auto;
			overflow-x:hidden;
			background:#fff !important;
		}
		</style>
	</body>
		
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
		<script type="text/javascript" src="<?php echo base_url() ;?>assets/datetimepicker/moment-with-locale.js" ></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<style>
.modal .close{
	margin:10px !important;
	color:#888 !important;
	opacity: 1 !important;
	font-size:2em !important;
	font-weight: normal !important;
}
.modal .close:hover{
	color:#000 !important;
}
</style>

</html>