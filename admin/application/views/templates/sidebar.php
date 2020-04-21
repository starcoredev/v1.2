<div class="sidebar">
	<div class="sidebar-bars">
		<label>Admin Area</label>
		<a href="javascript:void(0)" onclick="openSidebar()"><i class="fa fa-bars"></i><i class="fa fa-long-arrow-left"></i></a>
	</div>
	<div class="scrollbar-macosx sidebar-menu" >
	<ul>
		<li><a href="<?php echo base_url(); ?>" title="Dashboard"><i class="fa fa-home"></i><span>Dashboard</span><i class="fa fa-home"></i></a></li>
		<li><label>Master Data</label></li>
		<li><a href="<?php echo base_url(); ?>package" title="Category Package"><i class="fa fa-cubes"></i><span>Category Package</span><i class="fa fa-cubes	"></i></a></li>
		<li><a href="<?php echo base_url(); ?>client" title="Client List"><i class="fa fa-bank"></i><span>Client List</span><i class="fa fa-bank"></i></a></li>
		<li><a href="<?php echo PUBLICURL; ?>?auth=f" title="Logout"><i class="fa fa-sign-out"></i><span>Logout</span><i class="fa fa-sign-out"></i></a></li>
	</ul>
	</div>
</div>

<script>
function openSidebar(){
	if($(".sidebar").hasClass("open")){
		$(".sidebar").removeClass("open");
	}
	else{
		$(".sidebar").addClass("open");
	}
}
$(document).ready(function(){
    $('.scrollbar-macosx').scrollbar();
});
</script>