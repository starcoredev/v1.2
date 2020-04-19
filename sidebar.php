<div class="sidebar open">
	<a href="javascript:void(0)" onclick="openSidebar()" class="sidebar-control"><i class="fa fa-caret-right"></i><i class="fa fa-caret-left"></i></a>
	
	<div class="sidebar-top">
		<div class="sidebar-search">
			<a href="javascript:void" class="sidebar-search-bars"><i class="fa fa-bars"></i></a>
			<input type="text" id="s" name="s" autocomplete="off" placeholder="filter..." />
			<div class="sidebar-search-control">
				<a href="javascript:void" onclick="$('#s').focus()"><i class="fa fa-search"></i></a>
				<i class="split">|</i>
				<a href="javascript:void" onclick="$('#s').val('');$('#s').focus(); page=1; reFilter()()"><i class="fa fa-close"></i></a>
			</div>
		</div>
		
	</div>
	<h3 style="margin:0px; padding-left:10px; font-size:0.9em; font-weight:normal">Selamat Datang <b><?php echo $_SESSION["user"]["username"]; ?></b> dari Client <b><?php echo $_SESSION["user"]["client"]["name"]; ?></b></h3>
	<div class="scrollbar-macosx sidebar-layers" >
		<h3>Layers (multiple selections)</h3>
		<?php 
		$n = 0;
		foreach($tags as $t){
			$n++;
			
			if($t["icon"] != null){
				if($t["icon"]["package"] != null){
			
			$iconOnUrl = $base_url. 'assets/images/package/' . $t["icon"]["package"]["id"] . '/' . $t["icon"]["package"]["id"] . '_on.png?' . date("Ymdhis");
			$iconOffUrl = $base_url. 'assets/images/package/' . $t["icon"]["package"]["id"] . '/' . $t["icon"]["package"]["id"] . '_off.png?' . date("Ymdhis");
			$markerUrl = $base_url. 'assets/images/package/' . $t["icon"]["package"]["id"] . '/' . $t["icon"]["package"]["id"] . '_marker.png?' . date("Ymdhis");
			
			$name = ($t["icon"]["package"]["package_name"] != "" ? $t["icon"]["package"]["package_name"] : $t["name"]) ;
		?>
		<label id="l<?php echo $n; ?>" class="checkbox <?php echo ($n==1?'checked':''); ?> ">
			<img class="off" src="<?php echo $iconOffUrl; ?>" />
			<img class="on" src="<?php echo $iconOnUrl; ?>" />
			<?php echo $name; ?>
			<input type="checkbox" <?php echo ($n==1?'checked="checked"':''); ?> value="<?php echo str_replace("_"," ", $t["id"]); ?>">
			<span class="checkmark"></span>
		</label>
		<?php } 
			}
		}
		?>
		<!--<label id="l2" class="checkbox">Badan Usaha<input type="checkbox" value="badan usaha"><span class="checkmark"></span></label>
		<label id="l3" class="checkbox">Bumdes<input type="checkbox" value="bumdes"><span class="checkmark"></span></label>
		<label id="l4" class="checkbox">Pelaku Usaha<input type="checkbox" value="pelaku usaha"><span class="checkmark"></span></label>
		<label id="l5" class="checkbox">Layanan Publik<input type="checkbox" value="layanan publik"><span class="checkmark"></span></label>
		<label id="l6" class="checkbox">Store Observation<input type="checkbox" value="store observation"><span class="checkmark"></span></label>
		<label id="l7" class="checkbox">Bengkel Motor<input type="checkbox" value="bengkel motor"><span class="checkmark"></span></label>-->
		<br />
	</div>
	<div class="sidebar-lists" >
		<div class="scrollbar-macosx ">
			<!--<ul class="sidebar-lists-list">
				
			</ul>-->
			<table class="sidebar-lists-list table table-striped table-bordered" style="width:100%">
				<thead style="display:none"><tr><th>Name</th></tr></thead>
				<tbody>
					<tr class="loading"><td>Mengambil data...<div class="spinner-border"></div></td></tr>
				</tbody>
			</table>
		</div>
		<nav aria-label="Page navigation example" style="width:100%; background:#fff" >
		  <ul class="pagination pagination-sm justify-content-end  flex-wrap" style="margin-top:-15px; "></ul>
		</nav>
	</div>
	
	<div class="sidebar-detail">
		<div class="gallery">
			<?php include "slider.php" ;?>
		</div>
		<div class="go-back">
			<a href="javascript:void(0)" onclick="goHome()" ><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Back To Result</a>
		</div>
		<h2 id="d-title"></h2>
		<hr />
		
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#basic_info">Basic Info</a></li>
			<li><a data-toggle="tab" href="#detail_info">Defail Info</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane container active" id="basic_info">
				<div class="scrollbar-macosx sidebar-detail-content" >
					<div class="form-group">
						<img src="assets/images/ic_tag.png" />
						<!--<label>tag</label>-->
						<p id="d-tag"></p>
					</div>
					<div class="form-group">
						<img src="assets/images/ic_name.png" />
						<!--<label>name</label>-->
						<p id="d-name"></p>
					</div>
					<div class="form-group">
						<img src="assets/images/ic_address.png" />
						<!--<label>address</label>-->
						<p id="d-address"></p>
					</div>
				</div>
			</div>
			<div class="tab-pane container fade" id="detail_info">
				<div class="scrollbar-macosx sidebar-detail-content" ></div>
			</div>
		</div>
		
	</div>
</div>

<style>
.box-icon{
	border:1px solid #ccc;
}
</style>
<script>
function openSidebar(){
	if($(".sidebar").hasClass("open")){
		$(".sidebar").removeClass("open");
		$(".leaflet-left").removeClass("open");
	}
	else{
		$(".sidebar").addClass("open");
		$(".leaflet-left").addClass("open");
	}
}
$(document).ready(function(){
    $('.scrollbar-macosx').scrollbar();
	
	$(".sidebar .sidebar-detail .nav.nav-tabs li a").click(function(e){
		$(".sidebar .sidebar-detail .nav.nav-tabs li").removeClass("active");
		$(this).parent().addClass("active");
	});
});
</script>