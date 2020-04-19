
<!-- Page Content -->
<div class="page-title-2">
	<div class="col-md-8">
	<h1 style="display: inline-block">Detail Client | <b><?php echo strtoupper($data[0]["name"]); ?></b></h1>
	<a href="<?php echo base_url(); ?>client" style="display: inline-block; vertical-align: top; margin-top:12px; margin-left: 10px">Back to client</a></div>	
</div>

<div class="col-md-12 page-content after-title">
	<div class="container">
		<form class="form-horizontal label-left">
			<div class="col-md-9">
				<input type="hidden" name="key" value="<?php if($data){echo $data[0]["id"];} ?>" />
				<input type="hidden" name="crud" value="<?php echo $form; ?>" />

				<br />
				<div class="form-group">
					<label class="control-label col-sm-2" >Name:</label>
					<label class="control-label col-sm-8 align-left" ><?php echo $data[0]["name"]; ?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Max User:</label>
					<label class="control-label col-sm-1 align-left" ><?php echo $data[0]["max_user"]; ?> User</label>
					<label class="control-label col-sm-2" >Start Date:</label>
					<label class="control-label col-sm-2 align-left" ><?php echo $data[0]["start_date"]; ?></label>
					<label class="control-label col-sm-2" >End Date:</label>
					<label class="control-label col-sm-2 align-left" ><?php echo $data[0]["end_date"]; ?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Map Title:</label>
					<label class="control-label col-sm-8 align-left" ><?php echo $data[0]["map_title"]; ?></label>
				</div>
			</div>
		</form>
	</div>
	
	<div class="container" style="margin-top:10px;">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#icon">Data Category</a></li>
		  <li><a data-toggle="tab" href="#user">Data User</a></li>
		</ul>

		<div class="tab-content">
		  <div id="icon" class="tab-pane fade in active">
			<div style="text-align:right">
				<div class="btn-group">
					<a href="javascript:void(0)" onclick="showDialog('<?php echo $formData; ?>/icon/add')" class="btn btn-sm btn-success"><i class="fa fa-plus"></i>Add Icon</a>
					<a href="javascript:void(0)" onclick="refreshIcon(true)" class="btn btn-sm btn-info"><i class="fa fa-refresh"></i>Refresh Icon</a>
				</div>
			</div>
			<br />
			<div class="table-responsive">
				<table id="tableIcon" class="table table-striped">
					<thead>
						<th>No</th>
						<th>Tag Name</th>
						<th>Package Name</th>
						<th>Icon ON</th>
						<th>Icon OFF</th>
						<th>Icon Marker</th>
						<th class="action 1"></th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		  </div>
		  <div id="user" class="tab-pane fade">
			<div style="text-align:right">
				<div class="btn-group">
					<a href="javascript:void(0)" onclick="showDialog('<?php echo $formData; ?>/user/add')"  class="btn btn-sm btn-success"><i class="fa fa-plus"></i>Add User</a>
					<a href="javascript:void(0)" onclick="refreshUser(true)" class="btn btn-sm btn-info"><i class="fa fa-refresh"></i>Refresh User</a>
				</div>
			</div>
			<br />
			<div class="table-responsive">
				<table id="tableUser" class="table table-striped">
					<thead>
						<th>No</th>
						<th>Username</th>
						<th>Email</th>
						<th>Tag Filter</th>
						<th>Status</th>
						<th class="action 1"></th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		  </div>
		</div>
	</div>
</div>

<span class="xxxxxx"></span>

<script>

var iconTable, iconData, iconPackage;
$(document).ready(function(e){
	//setTimeout(function(){showPackageModal()(); }, 500);
	setTimeout(function(){refreshIcon(); }, 500);
	
});

function showModalDialog(str){
	showDialog(str, {});
}
function closeModalDialog(autoRefresh, i){
	$("#myModal").modal("hide");
	if(autoRefresh){
		if(i == 1)refreshIcon(true);
		else if(i == 2)refreshUser(true);
	}
}

function refreshIcon(onlyMe){
	var param = {
		getURL: "<?php echo $getData; ?>/icon",
		tableColumnCount: 5,
		actionColumn:[
			{item: '<a href="javascript:void(0)" onclick="showModalDialog(\'<?php echo $formData; ?>/icon/edit?key=[key]\')" title="Edit" class="btn btn-xs btn-primary" ><i class="fa fa-edit"></i>Edit</a>', keys: ["key"]}
		],
		tableFixHeader: ".page-title-2 .table-fix-header table"
	};
	iconTable = new MyDataTable("#tableIcon");
	iconTable.initialize(param);
	iconTable.refresh();
	
	if(!onlyMe)setTimeout(function(){refreshUser(); }, 500);
}

function refreshUser(onlyMe){	
	var param= {
		getURL: "<?php echo $getData; ?>/user",
		tableColumnCount: 4,
		actionColumn:[
			{item: '<a href="javascript:void(0)" onclick="showModalDialog(\'<?php echo $formData; ?>/user/edit?key=[key]\')" title="Edit" class="btn btn-xs btn-primary" ><i class="fa fa-edit"></i>Edit</a>', keys: ["key"]}
		],
		tableFixHeader: ".page-title-2 .table-fix-header table"
	};
	iconData = new MyDataTable("#tableUser");
	iconData.initialize(param);
	iconData.refresh();
}


</script>

<style>
.tab-content .tab-pane{
	padding:10px;
}
</style>