<div class="sidebar">
	<div class="sidebar-bars">
		<label>Admin Area</label>
		<a href="javascript:void(0)" onclick="openSidebar()"><i class="fa fa-bars"></i><i class="fa fa-long-arrow-left"></i></a>
	</div>
	<div class="scrollbar-macosx sidebar-menu" >
	<ul>
		<li><a href="<?php echo base_url(); ?>" title="Dashboard"><i class="fa fa-home"></i><span>Dashboard</span><i class="fa fa-home"></i></a></li>
		<li><label>Master Data</label></li>
		<li><a href="<?php echo base_url(); ?>package" title="Client"><i class="fa fa-cube"></i><span>Package</span><i class="fa fa-cube"></i></a></li>
		<li><a href="<?php echo base_url(); ?>client" title="Client"><i class="fa fa-bank"></i><span>Client</span><i class="fa fa-bank"></i></a></li>
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