<?php
//session_start();


include 'koneksi.php';

$err = "";
if(isset($_POST["login"])){
	$uid  = $_POST["uid"];
	$upwd = $_POST["upwd"];
	
	$data = execqueryreturnall("superadmin", "select * from superadmin where username = '".$uid."'");
	if(count($data) > 0){
		if($upwd == $data[0]["upwd"]){
			header("location:" . $base_url . '/admin?auth=t&un='.$uid);
			exit;
		}
		else{
			$err = "Maaf, Username atau password tidak valid";
		}
	}
	else{
		$data = execqueryreturnall("users", "select * from users where email = '".$uid."' and status = 1");
		if(count($data) > 0){
			$data = $data[0];
			if($data["upwd"] == $upwd){
				$ddd = execqueryreturnall("client", "select * from client where id = '".$data["client_id"]."'");
				
				if(count($ddd) > 0){
					$start = strtotime($ddd[0]["start_date"]);
					$end = strtotime($ddd[0]["end_date"]);
					$current = strtotime(date("Y-m-d"));
					
					//echo ((($current >= $start) && ($current <= $end)));
					if((($current >= $start) && ($current <= $end))){
						$data["client"] = $ddd[0];
						$_SESSION["user"] = $data;
						header("location: index.php");
						exit;
					}
					else{
						$err = "Maaf, Akses login anda tidak valid";
					}
					
				}
				else{
					$err = "Maaf, Data client anda tidak ditemukan";
				}								
			}
			else{
				$err = "Maaf, Username atau password tidak valid";
			}
		}
		else{
			$err = "Maaf, User tidak ditemukan";
		}
	}
}

?>

<html>
<head>
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed&display=swap" rel="stylesheet">

<style>
html, body{
	padding:0px;
	margin:0px;
	font-family:calibri;
}

.searchview{
	position:relative;
	border:1px solid #ccc;
	-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
	margin:30px;
	margin-top:20px;
	margin-bottom:20px;
}
.searchview input{
	width:91%;
	border:none;
	padding:10px;
	margin:1px;
	margin-left:7%;
	outline:0;
}
.searchview i.fa{
	position:absolute;
	left:0;
	margin:10px;
}

.content{
	display:table;
	position:fixed;
	right:0;
	top:0;
	margin:30px;
	margin-right:100px;
	text-align:center;
}
.content h2{
	font-weight:normal;
	font-size:2em;
}
.content h1, h2, h3{
	padding:0px;
	margin:0px;
	font-family: 'PT Sans Narrow', sans-serif;
	font-family: 'Ubuntu Condensed', sans-serif;
}
.content h3{
	text-align:left;
}
.content .box{
	width:300px;
	background:#eee;
	padding:10px;
	margin:0 auto;
	margin-top:30px;
	-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
}
.content .box .box-inside{
	background:#fff;
	padding-top:20px;
	padding-bottom:20px;
	-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
}
.content .box .box-inside h3{
	padding:5px 15px 5px 15px;
	background:#70ad47;
	color:#fff;
	font-size:0.9em;
}
.content .box .box-inside button{
	border:none;
	cursor:hand;
	text-decoration:none;
	padding:5px 15px 5px 15px;
	background:#70ad47;
	color:#fff;
	font-size:0.9em;
	-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
}
.content .box .box-inside i:last-child{
	display:block;
	margin-top:5px;
	color:red;
}
</style>
</head>
<body>
	<img src="assets/images/globe.png" />
	<div class="content">
		<h1>StarCore GEO</h1>
		<h2>Location Based Informations System</h2>
		<div class="box">
			<div class="box-inside">
				<form method="post" autocomplete="off">
					<h3>PLEASE LOGIN</h3>
					<div class="searchview">
						<i class="fa fa-user"></i>
						<input type="text" name="uid" class="form-control" placeholder="Email">
					</div>
					<div class="searchview">
						<i class="fa fa-key"></i>
						<input type="password" name="upwd" class="form-control" placeholder="Password">
					</div>
					<button name="login">LOGIN</button>
					<i><?php echo $err; ?></i>
				</form>
			</div>
		</div>
					
		<br /><br />
		<label>&copy; 2020 PT Exorty Indonesia. All Rights Reserved.</label>
	</div>	
</body>
</html>