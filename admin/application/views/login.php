<?php

$logoPath = IMAGEPATH."logo";
$logoSrc = IMAGESRC."logo";
$logo = $this->fileupload->findImageByName($logoPath, $logoSrc, "gif|jpg|png", "#");
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
		
		<title>Web Admin</title>
		<style>
			body{
				background:#f7f9fb;
				position:fixed;
				width:100%;
				height:100%;
			}
			.content{
				text-align:center;
				position: relative;
				top: 50%;
				transform: translateY(-50%);
			}
			#login{
				display:inline-block;
				width:400px;
			}
			#login .login-logo{
				background:#ffffff;
				width: 100px; height:100px;
				margin-bottom: 50px;
				-webkit-border-radius: 100px;
				-moz-border-radius: 100px;
				border-radius: 100px;
			}
			#login .login-content{
				text-align:left;
				background:#ffffff;
				padding:20px;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				border-radius: 5px;
				-webkit-box-shadow: 0px 0px 10px 0px rgba(235,235,235,1);
-moz-box-shadow: 0px 0px 10px 0px rgba(235,235,235,1);
box-shadow: 0px 0px 10px 0px rgba(235,235,235,1);
Copy Text
			}
			#login h2, #login label, #login span, #login input, #login a{
				display:block;
			}
			#login h2{	
				padding:0px; margin:0px; margin-bottom:30px;
				letter-spacing:2px;
			}
			#login button{
				width:100%;
				margin-top:30px;
			}
			
		</style>
	</head>
	<body>
		<div class="content">
			<form id="login" method="post" action="login/post">
				<img class="login-logo" src = "<?php echo $logo; ?>" />
				<div class="login-content">
					<h2><b>LOGIN</b></h2>
					<div class="form-group">
						<span>Email address</span>
						<input class="form-control" type="text">
					</div>
					<div class="form-group">
						<span>Password</span>
						<input class="form-control" type="password" />
					</div>
					<a href="#">Forgot Password</a>
					
					<button class="btn btn-primary">Login</button>
				</div>
			</form>
		</div>
	</body>
</html>