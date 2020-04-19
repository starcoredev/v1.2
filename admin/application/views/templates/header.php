<?php

$logoPath = IMAGEPATH."logo";
$logoSrc = IMAGESRC."logo";
$logo = $this->fileupload->findImageByName($logoPath, $logoSrc, "gif|jpg|png", "#");
?>
<nav class="navbar navbar-default navbar-fixed-top header">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url(); ?>">
		<img src = "<?php echo $logo; ?>" />
		<p><?php echo $this->session->userdata("admin")["name"]; ?><br /><span>Admin Area</span></p>
		</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse navbar-right" style="margin:0px;">
      <ul class="nav navbar-nav">
		<!--<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span><i class="fa fa-money"></i>
			<span class="baloon">0</span></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Pembayaran Kosong</a></li>
				<a href="<?php echo base_url(); ?>checkout" class="btn btn-success">Checkout</a>
			</ul>
		</li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span><i class="fa fa-envelope"></i>
			<span class="baloon">0</span></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Cart Kosong</a></li>
				<a href="<?php echo base_url(); ?>checkout" class="btn btn-success">Checkout</a>
			</ul>
		</li>-->
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;<?php echo $this->session->userdata("admin")["name"]; ?></a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo PUBLICURL; ?>client/resetpassword" class="btnx"><i class="fa fa-key"></i>&nbsp;&nbsp;Reset Password</a></li>
				<a href="<?php echo PUBLICURL; ?>?auth=f" class="btn btn-danger">Sign out</a>
			</ul>
		</li>
      </ul>
    </div><!--/.nav-collapse -->
</nav>
