
<!-- Page Content -->
<div class="page-title-2">
	<div class="col-md-6"><h1><i class="fa fa-archive"></i>Basic Info</h1></div>
	<div class="col-md-6">
		<div class="control">
			<a href="<?php echo $addURL; ?>" class="add"><i class="fa fa-plus"></i>&nbsp; Add</a>
			<span>|</span>
			<a href="javascript:void(0)" onclick="script.refresh()" class="refresh"><i class="fa fa-refresh"></i>&nbsp; Refresh</a>
		</div>
	</div>
	<div class="col-md-12 table-fix-header">
		<table class="table">
			<thead>
				<th>No</th>
				<th>Name</th>
				<th>Address</th>
				<th>Tag</th>
				<th class="action 1"></th>
			</thead>
		</table>
	</div>
</div>

<div class="col-md-12 page-content">
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
	<div class="container">
		<div class="table-responsive">
			<table id="table" class="table table-striped">
				<thead>
					<th>No</th>
					<th>Name</th>
					<th>Address</th>
					<th>Tag</th>
					<th class="action 1"></th>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>


<script>
var script;
$(document).ready(function(e){
	var param = {
		getURL: "<?php echo $getURL; ?>",
		tableColumnCount: 3,
		actionColumn:[
			{item: '<a href="<?php echo $editURL; ?>?key=[key]" title="Edit" class="btn btn-xs btn-primary" ><i class="fa fa-edit"></i>Edit</a>', keys: ["key"]},
			{item: '<a href="<?php echo base_url(); ?>detail-info?b=[key]" title="Detail Info" class="btn btn-xs btn-info" ><i class="fa fa-eye"></i>Detail Info</a>', keys: ["key"]}
		],
		actionStyle: 'width:200px; text-align:right',
		tableFixHeader: ".page-title-2 .table-fix-header table"
	};
	script = new MyDataTable("#table");
	script.initialize(param);
	script.refresh();
});
</script>
