
<!-- Page Content -->
<div class="page-title-2">
	<div class="col-md-6"><h1><i class="fa fa-archive"></i>Package</h1></div>
	<div class="col-md-6">
		<div class="control">
			<a href="javascript:void(0)" onclick="showModalDialog('<?php echo $addURL; ?>')" class="add"><i class="fa fa-plus"></i>&nbsp; Add</a>
			<span>|</span>
			<a href="javascript:void(0)" onclick="script.refresh()" class="refresh"><i class="fa fa-refresh"></i>&nbsp; Refresh</a>
		</div>
	</div>
	<div class="col-md-12 table-fix-header">
		<table class="table">
			<thead>
				<th>No</th>
				<th>Tag</th>
				<th>Package Name</th>
				<th>Icon ON</th>
				<th>Icon OFF</th>
				<th>Icon Marker</th>
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
					<th>Tag</th>
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
</div>


<script>
var script;
$(document).ready(function(e){
	setTimeout(function(){refreshData();}, 100);
});

function refreshData(){
	var param = {
		getURL: "<?php echo $getURL; ?>",
		tableColumnCount: 5,
		actionColumn:[
			{item: '<a href="javascript:void(0)"  onclick="showModalDialog(\'<?php echo $editURL; ?>?key=[key]\')" title="Edit" class="btn btn-xs btn-primary" ><i class="fa fa-edit"></i>Edit</a>', keys: ["key"]},
			{item: '<a href="javascript:void(0)"  onclick="duplicate(\'[key]\')" title="Duplicate" class="btn btn-xs btn-info" ><i class="fa fa-copy"></i>Duplicate</a>', keys: ["key"]}
		],
		actionStyle: " width:150px ",
		tableFixHeader: ".page-title-2 .table-fix-header table"
	};
	script = new MyDataTable("#table");
	script.initialize(param);
	script.refresh();
}

function showModalDialog(str){
	showDialog(str, {});
}
function closeModalDialog(autoRefresh){
	$("#myModal").modal("hide");
	if(autoRefresh){
		refreshData();
	}
}

function duplicate(str){
	var c = confirm("Apakah anda yakin untuk menduplikasi data ini?. Pilih OK untuk melanjutkan.");
	if(!c)return false;

	var data = {"id":str};
	$.ajax({type: "POST", url: "<?php echo $duplicateURL; ?>", dataType: 'json', data: data,
			success: function(data){		
				alert(data[0].msg);
				if(data[0].status == 1){
					setTimeout(function(){refreshData();}, 100);
				}
				else{
				}
			},
			error: function (data) {	
				alert(JSON.stringify(data));
				showAlert("#form .alert", btn, "alert-danger", JSON.stringify(data));
			},
			complete : function () {
				
			}
		});
}

</script>
