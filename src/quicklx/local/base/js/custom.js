	
	
(function ($) {
	function initSearchFilter() {
		$(".usr-srch-field input[type='checkbox']").each(function(){
			var that = this;
			var targets = $(this).data("target");
			$(targets).each(function (){
				if(this.id=="id_activestatus"){
					if(this.value!="0")
						that.checked = true;
				}
				else if(this.id == "id_eventtype") {
					if(this.value != "0")
						that.checked = true;
				}
				else if(this.id == "id_country") {
					if(this.value != "0")
						that.checked = true;
				}
/*				else if(this.id == "id_organization") {
					if(this.value != "0")
						that.checked = true;
				}*/
				else if( this.id == "id_user") {
					if(this.value != "0")
						that.checked = true;
				}
				else if( this.id == "id_completionstatus") {
					if(this.value != "0")
						that.checked = true;
				}
				else if( this.id == "id_enrolledstatus") {
					if(this.value != "0")
						that.checked = true;
				}
				else if( this.id == "id_license") {
					if(this.value != "0")
						that.checked = true;
				}
				else if( this.id == "id_subgroup") {
					if(this.value == "0")
						that.checked = false;
					else if(this.value != "") {
						that.checked = true;
					}
				}
				else if( this.id == "id_course") {
					if(this.value == "0")
						that.checked = false;
					else if(this.value != "") {
						that.checked = true;
					}
				}
				else if(this.value != "") {
					that.checked = true;
				}
			});
		});
		
		$(".remove-btn").click(function(){
			$(".usr-srch-field input[type='checkbox']").each(function(){
			var that = this;
			var targets = $(this).data("target");
			$(targets).each(function (){
				if(this.id=="id_activestatus"){
					this.value="0";
					that.checked = false;
					
				}
				else if(this.id == "id_eventtype") {
					this.value = "0";
					that.checked = false;
				}
				else if(this.id == "id_country") {
					this.value = "0";
					that.checked = false;
				}
/*				else if(this.id == "id_organization") {
					this.value = "0";
						that.checked = false;
				}*/
				else if( this.id == "id_user") {
					this.value = "0";
					that.checked = false;
				}
				else if( this.id == "id_completionstatus") {
					this.value = "0";
					that.checked = false;
				}
				else if( this.id == "id_enrolledstatus") {
					this.value = "0";
					that.checked = false;
				}
				else if( this.id == "id_license") {
					this.value = "0";
					that.checked = false;
				}
				else if(this.value != "") {
					this.value = "";
					that.checked = false;
				}
				$(this).closest(".fitem").removeClass("show");

				});
			});
		});
		
		$(".go-btn").click(function(){
			$(".usr-srch-field input[type='checkbox']").each(function (){
				var add = this.checked;
				var targets = $(this).data("target");
				$(targets).each(function (){
					if(add)
						$(this).closest(".fitem").addClass("show");
					else
						$(this).closest(".fitem").removeClass("show");
				});
			});
			$(".usr-srch-field").hide();
		});
		$(".go-btn").click();
	}
	function dateFilter() {
		
		var daterange = $('#id_daterange').val();

		if(daterange == 'no' )
				$(".datefilter-elements .fitem").removeClass("show");
		else
			$(".datefilter-elements .fitem").addClass("show");

			
	}

	function enddateCheck() {
		
		if ($('#enddatecheck').prop('checked')) {
				$("#enddate").closest(".form-group").addClass("show"); //checked
		}
		else {
		   $("#enddate").closest(".form-group").removeClass("show"); //not checked
		}

			
	}
	function initscheduleFilter() {
		
		$.each($(".schedule-elements .form-group"), function(){
			$(this).removeClass("show");
		});
		
			$.each($("#schedule option:selected"), function(){ 
			var that = this;
				//console.log(this.value); 
			if(this.value == "Once") {
				    $("#enddatecheck").prop('checked', false); 
				    $("#enddate").closest(".form-group").removeClass("show");
				} 
			else if	(this.value == "Monthly") 
					$("#opt31first").prop('checked', true);
   
				var add = this.selected;
			var targets = $(this).data("target");
			$(targets).each(function (){
				if(add)
					$(this).closest(".form-group").addClass("show");
				else
					$(this).closest(".form-group").removeClass("show");
												
			});
			if(targets){
				if(targets.includes("enddatecheck"))
						$("#enddatecheck").closest(".form-group").addClass("show");
			}
			else
				$("#enddatecheck").closest(".form-group").removeClass("show");
		});
	}
	function loadsubgroup() {
		var companyid = $("#id_organization").val();        
	        var subgroup = $(".filter-elements #id_subgroup").val();  
		if(typeof companyid !== 'undefined' &&  companyid.length > 0){ 	
		       $.getJSON("../base/load_subgroup.php?companyid="+companyid, function( data ) {
        	       // console.log(data);
	                var items = [];
        	        $.each( data, function( key, val ) {
			if($.inArray(key,subgroup)!='-1')
        	                items.push( "<option value='" + key + "' selected>" + val + "</option>" );
                	    else
	                        items.push( "<option value='" + key + "'>" + val + "</option>" );
        	        });

	                $(".filter-elements #id_subgroup ").html( items.join( "" ) );
        	    });
		}
	/*	else{
			var items = [];
			items.push( "<option value='0' selected>Select a Group first</option>" );
			$(".filter-elements #id_subgroup ").html( items.join( "" ) );

		}  */    
    }
	$( document ).ready(function() {
			initSearchFilter();
		 loadsubgroup();
	        $("#id_organization").change(function(){
        	        loadsubgroup();
	        }); 
		$('#starttime').timepicker();

	     var className = $('#id_daterange').attr('class');
		$("#activesearch").attr('class',className);
 
	//	$(".chosen-select").chosen();     
$(".chosen-select").chosen().after("<div class='dup-choices'><ul class=''>No selection</ul></div>");
		
		var $ul = $(".dup-choices").find("ul");
		$ul.empty().html($(".chosen-choices").html());
			
		
		$(".chosen-select").chosen().change( function (){
			var $ul = $(this).next(".dup-choices").find("ul");
			$ul.empty().html($(this).siblings(".chosen-container-multi").find(".chosen-choices").html());
			
		});
		
		$(".dup-choices").on("click",".search-choice-close",function(){
			var $selLi = $(this).closest("li");
			var selNum = $selLi.index();
			
			$(this).hide();
			$selLi.hide();
			
			$(this).closest(".dup-choices").next(".chosen-container-multi").find(".chosen-choices .search-choice").eq(selNum).find("a").click();
			var that = this;
			setTimeout(function(){ 
				$(".dup-choices").siblings("select").change();
				//alert("1");
			}, 30); 
			
			$selLi.hide();
			
		});
		
		
        $(".singlebutton button").attr("class"," ");           
		$(".go-btn").removeClass("btn btn-secondary");
		$("#id_removefilter").removeClass("btn btn-secondary");
		$('button[name=emailreport]').removeClass("btn btn-secondary");
		$('button[name=schedulereport]').removeClass("btn btn-secondary");
	
		$(".singlebutton button").addClass("btn m-3 btn-primary");
		$(".go-btn").addClass("btn btn-primary");
		$("#id_removefilter").addClass("btn btn-primary");
		$('button[name=emailreport]').addClass("btn btn-primary");
		$('button[name=schedulereport]').addClass("btn btn-primary");
		
		$("#opt31second").click(function(){
			$(this).closest(".form-group").removeClass("show");
			$("#opt311").closest(".form-group").addClass("show");
			$("#opt311second").prop('checked', true);
			
					
		});
		
		$("#opt311first").click(function(){
			$(this).closest(".form-group").removeClass("show");
			$("#opt31").closest(".form-group").addClass("show");
			$("#opt31first").prop('checked', true);
					
		});
		
		initscheduleFilter();

		$("#schedule").change(function(){
               	initscheduleFilter();

        });
        
		$("#scheduleinputuser").click(function(){
			    var emailusers = $('#emailusers').val();
			    if(emailusers)
					emailusers =emailusers+';';
				else
				emailusers = '';
				var allusers = emailusers+$(this).val();
				$("#emailusers").val(allusers);
        });
        
		$("#inputuser").click(function(){
			    var emailusers = $('#inputEmail').val();
			    if(emailusers)
					emailusers =emailusers+';';
				else
				emailusers = '';
				var allusers = emailusers+$(this).val();
				$("#inputEmail").val(allusers);
        });
        
        $('#enddatecheck').change(function() {
					enddateCheck();
		});
		
		
		dateFilter();
		enddateCheck();
		
		$("#id_daterange").change(function(){
			  		dateFilter();
	  
        });
		
		$("#id_perpage").change(function(){
			var perpage = $('#id_perpage').val();
			$(".mform").append(' <input type="hidden" name="perpage" value="'+perpage+'">');
			$("#id_submitbutton").click();
		});
	
		$("#schedulersubmitForm").click(function() {
			var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
			var description = $('#description').val();
			var email = $('#emailusers').val();
			var emailsubject = $('#scheduleEmailSubject').val();
			var emailbody = $('#scheduleEmailBody').val();
			var format =$('input[name=scheduleformat]:checked').val();
		   // var attach =$('input[name=attach]:checked').val();
			var startrangevalue = $('#startrange').val();
			var endrangevalue = $('#endrange').val();
			startrangevalue = startrangevalue.split('-');
			startrangeorder = startrangevalue[0];
			startrange = startrangevalue[1];
			
			endrangevalue = endrangevalue.split('-');
			endrangeorder = endrangevalue[0];
			endrange = endrangevalue[1];
			
			var schedule = $('#schedule').val();
			var starttime = $('#starttime').val();
			var startdate = $('#startdate').val();
			var enddate = $('#enddate').val();
			var enddatecheck =$('input[name=enddatecheck]:checked').val();
//console.log(starttime);
			var opt1=0;
			var opt2=0;
			var opt3=0;
			var opt4=0;
			if(schedule == 'Once'){
					var enddate = 0;

			}
			if(schedule == 'Daily'){
					var opt1 =$('input[name=dayopt1]').val();

			}

			if(schedule == 'Weekly'){
				var opt1 =$('input[name=weekopt1]').val();
				var opt2 = [];
					$.each($("input[name=weekopt2]:checked"), function(){            
						opt2.push($(this).val());
					});
			}
			if(schedule == 'Monthly'){
					var opt2checked =$('input[name=opt31]:checked').val();
					if(opt2checked=='month2'){
						opt1=0;
						var opt2 = $("#day option:selected").val();
						var opt3 = $("#weeks option:selected").val();
					}
					else
						var opt1 =$('input[name=monthopt1]').val();
				
				  var opt4 = [];
				$.each($("input[name=month]:checked"), function(){            
					opt4.push($(this).val());
				});

			}
			var hiddenparams='';
			$(".modal-body input[type='hidden']").each(function(){
					var that = this;	
					hiddenparams +=	'&'+this.name+'='+this.value;	
					
				
				});
			
			 
			 var today = new Date().getTime();
			 var today = today-86400000;
			var start = new Date(startdate).getTime();

			if(email.trim() == '' ){
				alert('Please enter your email.');
				$('#emailusers').focus();
				return false;
			}else {
				arr = email.split(';');
				for(i=0; i < arr.length; i++){	
					var tempemail = arr[i];	
					 if(tempemail.trim() != '' && !reg.test(tempemail)){
						alert('Please enter valid email.');
						$('#emailusers').focus();
						return false;
					}
				}  
			}
			if(emailsubject.trim() == '' ){
				alert('Please enter email subject .');
				$('#scheduleEmailSubject').focus();
				return false;
			}else if(emailbody.trim() == '' ){
				alert('Please enter email body.');
				$('#scheduleEmailBody').focus();
				return false;
			}
			else if(start < today){
				alert('Start date should be greater than today.');
				$('#startdate').focus();
				return false;
			}
			else if(enddatecheck == 'on' && new Date(enddate) <= new Date(startdate)){
				alert('End date should be greater than start date.');
				$('#enddate').focus();
				return false;
			}
			else if(startrangeorder > 0 && endrangeorder > startrangeorder){
				alert('End range should be greater than start range.');
				$('#endrange').focus();
				return false;
			}
		/*	else if(!starttime ){
				alert('Please check Start time (00:00:00).');
				$('#starttime').focus();
				return false;
			}*/
			else{
				//console.log('contactFrmSubmit=1&description='+description+'&emailsubject='+emailsubject+'&emailusers='+email
				 //  +'&emailbody='+emailbody+'&format='+format+'&opt1='+opt1+'&opt2='+opt2+'&opt3='+opt3+'&opt4='+opt4
				 // +'&schedule='+schedule+'&starttime='+starttime+'&startdate='+startdate+'&enddate='+enddate
				  // 	+'&endrange='+endrange+'&startrange='+startrange+hiddenparams);
				$.ajax({
					type:'POST',
					url:'../base/scheduler_form.php',
					data:'contactFrmSubmit=1&description='+description+'&emailsubject='+emailsubject+'&emailusers='+email
					+'&emailbody='+emailbody+'&format='+format+'&opt1='+opt1+'&opt2='+opt2+'&opt3='+opt3+'&opt4='+opt4
					+'&schedule='+schedule+'&starttime='+starttime+'&startdate='+startdate+'&enddate='+enddate
					+'&endrange='+endrange+'&startrange='+startrange+hiddenparams,
					beforeSend: function () {
						$('.submitBtn').attr("disabled","disabled");
						$('.modal-body').css('opacity', '.5');
					},
					success:function(msg){
						//console.log(msg);
						if(msg == 'ok'){
							$('.schedulestatusMsg').html('<span style="color:green;"><b>Schedule report created.</b></p>');
						}else{
							$('.schedulestatusMsg').html('<span style="color:red;"><b>An unexpected error occurred, please try again.</b></span>');
						}
						$('#schedulereportForm').scrollTop(0);

						$('.submitBtn').removeAttr("disabled");
						$('.modal-body').css('opacity', '');
					}
				});
			}
		});
		$("#emailsubmitForm").click(function() {
			var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
			var email = $('#inputEmail').val();
			var emailsubject = $('#inputEmailSubject').val();
			var emailbody = $('#inputEmailBody').val();
			var format =$('input[name=format]:checked').val();
			var attach =$('input[name=attach]:checked').val();
			var userarrayid = $('input[name=userarrayid]').val();
			var hiddenparams='';
			$(".modal-body input[type='hidden']").each(function(){
					var that = this;	
					hiddenparams +=	'&'+this.name+'='+this.value;	
					
				
				});
			var users = [];
				$.each($("#inputuser option:selected"), function(){            
					users.push($(this).val());
				});
			   
			if(email.trim() == '' ){
				alert('Please enter your email.');
				$('#inputEmail').focus();
				return false;
			}else {
				arr = email.split(';');
				for(i=0; i < arr.length; i++){	
					var tempemail = arr[i];	
					 if(tempemail.trim() != '' && !reg.test(tempemail)){
						alert('Please enter valid email.');
						$('#inputEmail').focus();
						return false;
					}
				}  
			}
			if(emailsubject.trim() == '' ){
				alert('Please enter email subject .');
				$('#inputEmailSubject').focus();
				return false;
			}else if(emailbody.trim() == '' ){
				alert('Please enter email body.');
				$('#inputEmailBody').focus();
				return false;
			}
			else{
				var url = $('input[name=url]').val();
				console.log(url+'contactFrmSubmit=1&emailsubject='+emailsubject+'&toemail='+email+'&emailbody='+emailbody
			        +'&format='+format+'&attach='+attach+hiddenparams);
				$.ajax({
					type:'POST',
					url: url+'.php',
					data:'contactFrmSubmit=1&emailsubject='+emailsubject+'&toemail='+email+'&emailbody='+emailbody
					+'&format='+format+'&attach='+attach+hiddenparams,
					beforeSend: function () {
						$('.submitBtn').attr("disabled","disabled");
						$('.modal-body').css('opacity', '.5');
					},
					success:function(msg){
						console.log(msg);
						if(msg == 'ok'){
							$('.statusMsg').html('<span style="color:green;"><b>Email sent to selected users.</b></p>');
						}else{
							$('.statusMsg').html('<span style="color:red;"><b>An unexpected error occurred, please try again.</b></span>');
						}
						$('#modalForm').scrollTop(0);
						$('.submitBtn').removeAttr("disabled");
						$('.modal-body').css('opacity', '');
					}
				});
			}
		});
		
			
	});
					
}(jQuery));
var x;
