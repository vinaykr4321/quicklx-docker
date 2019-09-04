$(document).ready(function(){


var currentURL = window.location.href;
var arr = currentURL.split('/');
var lastvalue = arr[arr.length-1];
if(lastvalue == 'createkeys.php' || lastvalue == 'createkeys.php?err=limit' ){
get_company($('#id_company').val());
}

$('#id_company').on('change', function(){
var company = $(this).val();
get_company(company);
});

$('#id_noofkeys').on('change', function(){
var enterdvalue = $(this).val();
var companyid = $('#id_company').val();
get_maxuse(enterdvalue,companyid);
});


function get_company(company_id){
$.ajax({
url: "createkeys_ajax.php",
type: "GET",
data: {action: "get_companyid", company: company_id},
success: function(data){
//alert(data);


var res = JSON.parse(data);
var course_options = "";
for(course in res){course_options += '<option value="'+res[course].cid+'">'+res[course].fullname+'</option>';}
//alert(course_options);
$('#id_selectlicense').html("");
$('#id_selectlicense').css({"width":"40%"});
$('#id_selectlicense').attr('size','10');
$('#id_selectlicense').append(course_options);

},
error: function(err){alert("Sorry, some thing went wrong, please try again.");}
});
}



function get_maxuse(enterdvalue,companyid){
$.ajax({
url: "createkeys_ajax.php",
type: "GET",
data: {action: "get_maxuse", inputvalue: enterdvalue,company: companyid},
success: function(getdata){
	//alert(getdata);

$("#id_error_noofkeys").show();
$("#id_error_noofkeys").css('color', 'red');
$("#id_error_noofkeys").text(getdata);



},
error: function(err){alert("Sorry, some thing went wrong, please try again.");}
});
}


 $("#page-local-report_createkeys-index form").submit(function(){
if($("#id_selectlicense").val() == '' ){
$("#id_selectlicense").css('border-color', '#f96868');
$('.col-md-3 label[for="id_selectlicense"]').css('color','#f96868');

}else{
	$("#id_selectlicense").css('border-color', '#76838f');
$('.col-md-3 label[for="id_selectlicense"]').css('color','#bbb');

}

/*if($('#id_keytype').val() == '0'){
	$('.col-md-3 label[for="id_keytype"]').css('color','#f96868');
	$("#id_keytype").css('border-color', '#f96868');
	off();
}else{
	$('.col-md-3 label[for="id_keytype"]').css('color','#76838f');
	$("#id_keytype").css('border-color', '#bbb');
}*/

});

$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    }
    return decodeURI(results[1]) || 0;
}
if($.urlParam('err') == 'limit'){
alert('Please insert key value less then or equal to 50000');
	}





var ids = $.urlParam('id');
$('#id_regkey').on('change', function(){
var keyvalue = $(this).val();
check_value(keyvalue,ids);
});



function check_value(keyvalue,companyid){

require(['core/config'], function (mdlcfg) {
$.ajax({
url: mdlcfg.wwwroot + "/local/report_createkeys/validate_key.php",
type: "GET",
data: {action: "key_validate", getkeyvalue: keyvalue,company: companyid},
success: function(getdata){
	

$("#id_error_regkey").show();
$("#id_error_regkey").css('color', 'red');
$("#id_error_regkey").text(getdata);



},
error: function(err){alert("Sorry, some thing went wrong, please try again.");}
});
});

}





});