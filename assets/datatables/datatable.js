$(document).ready(function(e){
	$(window).scroll(function(){
		checkScrollPosition();
	});
	checkScrollPosition();
});

function checkScrollPosition(){
	if($(window).scrollTop() > 10){
		$(".page-title-2").addClass("scroll");
	}
	else{
		$(".page-title-2").removeClass("scroll");
		$(".page-title-2.scrollview").addClass("scroll");
	}
}

var MyDataTable = function(table){
	var table				= $(table);
	var data				= [];
	
	MyDataTable.prototype.initialize = function(param){
		var parent = this;
		var keys = Object.keys(param);
		
		for(var i = 0; i < keys.length; i++){
			var key = keys[i];
			if(param[key])parent[key] = param[key];
		}
		
	};
	
	MyDataTable.prototype.setGetURL = function(url){
		var parent = this;
		parent.getURL = url;
	};
	
	MyDataTable.prototype.refresh = function(){
		var parent = this;
		parent.setnotif('Mengambil data');
		
		$.ajax({type: "POST", url: parent.getURL, dataType: 'json', data: data, 
			success: function(data){
				parent.data = data;
				parent.build();
			},
			error: function (data) {
				parent.setnotif(JSON.stringify(data));
			table.DataTable();
			}
		});
	};
	
	MyDataTable.prototype.build = function(){
		var parent = this;
		var items = "";
		var n = 0;
		
		$.each(parent.data, function(index, array){
			n++;
			items += '<tr>';
			items += '<td>'+n+'</td>';
			
			for(var col = 1; col <= parent.tableColumnCount; col++){
				var val = array[col];
				
				if(parent.columnFormat){
					if(parent.columnFormat[col]){
						if(parent.columnFormat[col] == "currency")val = val.StringToCurrencyID(".", ",");
					}
				}
				
				items += '<td>'+val+'</td>';
			}
			
			var actionStyle = "";
			if(parent.actionStyle)actionStyle=parent.actionStyle;
			items += '<td class="action1" style="'+actionStyle+'">';
			for(var ac = 0; ac < parent.actionColumn.length; ac++){
				var actionColumnValue = parent.actionColumn[ac];
				var actionColumnItem = parent.actionColumn[ac].item;
				for(var ack = 0; ack < actionColumnValue.keys.length; ack++){
					var ackk = actionColumnValue.keys[ack];
					actionColumnItem = actionColumnItem.replace('['+ackk+']', array[ackk]);
				}
				items += actionColumnItem;
			}
			items += '</td>';
			items += '</tr>';
		});
		table.children("tbody").html(items);		
		
		
		try{
			table.DataTable();
			parent.syncTableFixedHeader();
		}
		catch(err){
			alert(err.message);
		}
	};
	
	MyDataTable.prototype.syncTableFixedHeader = function(){
		$(parent.tableFixHeader).width(table.width());
		table.find("thead th").each(function(index, object){
			$(parent.tableFixHeader+" table thead th").eq(index).css("min-width", $(object).outerWidth()+"px");
		});
	};
	
	MyDataTable.prototype.setnotif = function(str){
		table.children("tbody").html('<tr><td colspan="100" style="text-align:center">'+str+'</td></tr>');
	};
}