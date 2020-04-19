<div class="page-title"><i class="fa fa-home"></i>Dashboard</div>
<div class="col-md-12 page-content">
	<div class="container">
		Selamat datang <b><?php echo $this->session->userdata("admin")["name"]; ?></b>
	</div>
	
</div>

<style>
.page-content .container{
	margin-top:10px;
	padding:10px;
	font-size:1.5em;
}
.block{
	display:block;
	margin-top:10px;
}
.box{
	padding:10px;
	text-align:left;
	vertical-align:top;
	-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
}
.box i, .box h1, .box h3{
	color:#fff;
}
.box i {
	font-size:3em; font-weight:bold;
	float:left; margin-left:15%; margin-top:2%; margin-right: 10%;
}
.box h3{
	margin:0px; margin-top:10px; font-weight:bold; letter-spacing: 3px;
}
.box h1{
	margin:0px; font-weight:bold; text-align:center;
}
.box.box-sm {
	vertical-align:top;
	text-align:right;
}
.box.box-sm i,
.box.box-sm h3,
.box.box-sm h1{
	display:inline-block;
	font-size:1.5em;
	margin:0px; padding:0px; 
}
.box.box-sm h1{
	margin-left:10px;
}
.box.box-sm h3{
	padding-left:20px;
	padding-right:10px;
	border-right:1px solid #fff;
}
</style>


