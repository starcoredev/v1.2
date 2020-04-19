$(document).ready(function(e){
	$('form').submit(function(e){
		e.preventDefault();
	});
});

$.fn.formToJSON = function(){
	var json	= {};
	var form	= this;
	
	$(form).find('input:hidden, input:text, input:password, input:file, select, textarea')
	.each(function() {
		var name = $(this).attr('name');
		var value = $(this).val();
		
		json[name] = value;
		
	});
	
	return json;
}
$.fn.JSONToForm = function(json){
	var form	= this;
	
	$(form).find('input:hidden, input:text, input:password, input:file, select, textarea')
	.each(function() {
		var name = $(this).attr('name');
		
		var value = json[name];
		if(value != null){
			$(this).val(json[name]);	
		}
		else{
			$(this).val('');
		}
		
	});
	
	return json;
}
Array.prototype.move = function (old_index, new_index) {
    if (new_index >= this.length) {
        var k = new_index - this.length;
        while ((k--) + 1) {
            this.push(undefined);
        }
    }
    this.splice(new_index, 0, this.splice(old_index, 1)[0]);
    return this; // for testing purposes
};
$.fn.resetForm = function(){
	var form = this;
	$(form).find('input:hidden, input:text, input:password, input:file, select, textarea')
	.each(function() {
		$(this).val('');
		
	});
}


Date.prototype.toFormatID = function () {
    var monthID = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
	var day 	= this.getDate();
	if(parseInt(day) < 10){
		day = '0' + day;
	}
	var month	= monthID[this.getMonth()];
	var year	= this.getFullYear();
	
	var v = day + " " + month + " " + year;
	
    return v; // for testing purposes
};

function scrollTo(content, target){
	$(content).animate({
		scrollTop: 0
	}, 0);
	
	$(content).animate({
        scrollTop: $(target).offset().top - $(content).offset().top - 10
    }, 800);
}

function showDialog(target, param){
	$("#myModal").modal();
	
	$.ajax({type: "POST", url: target, dataType: 'html', data: param, 
		success: function(data){
			//alert(data);
			//alert(JSON.stringify(data));
			$("#myModal .modal-content .modal-body").html(data);
		},
		error: function (data) {
			//alert(data);
			//alert(JSON.stringify(data));
			$("#myModal .modal-content .modal-body").html(JSON.stringify(data));
		}
	});
	//$("#myModal .modal-body").load(target);
}

function showAlert(target, btn, type, msg){
	$(target).removeClass("alert-success");
	$(target).removeClass("alert-warning");
	$(target).removeClass("alert-danger");
	
	$(target).html(msg);
	$(target).addClass(type);
	$(target).alert();
	$(target).show();
	
	$(btn).button('reset');
}

String.prototype.StringToCurrencyID = function(point, comma){
	try{
		var x = this;
		var parts = x.toString().split(point); //"."
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, point); //","
		return parts.join(comma);
	}
	catch(err){
		alert("ok");
		return "0";
	}
};

String.prototype.CurrencyToStringID = function(point, comma){
	var x = this;
	var parts = x.toString().split(point); //"."
	parts[0] = parts[0].split(comma).join("");
	return parts.join(comma);
};

Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };