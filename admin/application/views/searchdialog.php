
<div class="table-responsive">
	<table id="table" class="table table-striped">
		<thead>
			<?php

			if(count($data) > 0){
			?>
			<th>No</th>
			<?php
				foreach($data[0] AS $key => $name) {
						if(!in_array($key, $hidecolumns)){
							echo '<th>'.strtoupper(str_replace("_", " ", $key)).'</th>';
						}
				}
			}
			else{
				echo '<th>Data tidak ditemukan</th>';
			}
			?>
			<th class="action 1"></th>
		</thead>
		<tbody>
			<?php 
			$n = 0;
			foreach($data as $d){
				$n++;

				$cl = " btn-default ";
				if($d[$selected_column] == $selected_value){
					$cl = " btn-primary ";
				}

				echo '<tr>';
				echo '<input type="hidden" value = "'.$n.'" />';
				echo '<td>'.$n.'</td>';
				
				foreach($d AS $key => $name) {
					if(!in_array($key, $hidecolumns)){
						echo '<td>'.$name.'</td>';
					}
				}
				
				echo '<td>';
				
				if($multiselect == "true"){
					echo '<input type="checkbox" />';
				}
				else{
					$x = 'sdradio' . rand(1, 999999);
					echo '<input id="'.$x.'" name="radio" type="radio" style="display:none" />';
					echo '<button class="btn '.$cl.' btn-sm" onclick="$(\'#'.$x.'\').attr(\'checked\', true); dialogPilih()"><i class="fa fa-edit" style="margin-right:0px"></i>&nbsp;&nbsp;&nbsp;Select</button>';
				}
				
				echo '</td>';
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
</div>
<div>
<hr />
<?php if($multiselect == "true"){ ?>
<button class="btn btn-sm btn-success" onclick="dialogPilih()"><i class="fa fa-check"></i>&nbsp;&nbsp;Pilih</button>
<?php } ?>
<button class="btn btn-sm btn-danger" onclick="dialogCancel()"><i class="fa fa-close"></i>&nbsp;&nbsp;Batal</button>
</div>

<script>
var data = <?php echo json_encode($data); ?>;
$("#table").DataTable();
$("#table input").change(function(e){
	addSelected(this);
});

var multiselect = <?php if($multiselect == "true"){echo "true";}else{echo "false";} ?>;
var selectedData = [];

function addSelected(el){
	
	return false;
	var index = $(el).parent("td").parent("tr").find("input[type=hidden]").val();
	
	if(multiselect){
		selectedData.push(data[index-1]);
	}
	else{
		selectedData = [];
		selectedData.push(data[index-1]);
	}
}

function dialogPilih(){
	selectedData = [];
	$('#table tr td input').each(function(i, obj) {
		
		if($(this).is(":checked")){
			var index = $(obj).parent("td").parent("tr").find("input[type=hidden]").val();
			
			if(multiselect){
				selectedData.push(data[index-1]);
			}
			else{
				selectedData = [];
				selectedData.push(data[index-1]);
			}
		}
		
	});
	
	window["<?php echo $callback; ?>"](selectedData);
}

function dialogCancel(){
	window["<?php echo $cancel; ?>"]();
}

</script>

<style>
#table .value{
	
}
</style>