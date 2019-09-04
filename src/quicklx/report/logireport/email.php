<?php

require_once('../../config.php');
require_once('classes/user.php');

require_once($CFG->dirroot.'/report/logireport/filter_form.php');  // used for user report filtering
if (1==1) 
{
	?>
	<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

	<button class="btn btn-inverse mr-5 value="Email (Still working on it)" data-toggle="modal" data-target="#emailModal">Email (Still working on it)</button>
	<div class="container">
	  

	  	<!-- Modal -->
	  	<div class="modal fade" id="emailModal" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Modal Header</h4>
	        </div>
	        <div class="modal-body">
	          




	          <div id="rdMainBody">
	                <span id="divIfNoSessionError">
	                    <table id="rdRows-1">
	                        <tbody>
	                            <tr id="rowTo">
	                                <td>
	                                    <span id="lblSendToEmail" class="ThemeHeader">Send To Email Addresses:</span>
	                                    <br>
	                                    <span>
	                                        <textarea rows="3" cols="50" id="taEmailTo" name="taEmailTo" class="rdThemeInput"></textarea>
	                                    </span>
	                                    <br>
	                                    <br>
	                                </td>
	                            </tr>
	                            <tr id="rowSelectAddresses">
	                                <td>
	                                    <span id="lblSelectAddresses">Please select users to email:</span>
	                                    <br>
	                                        <select size="10" >
	                                            <option row="1" value="kevins@careeracademy.com">johndtest (John D)</option>
	                                        </select>
	                                    <br>
	                                    <br>
	                                </td>
	                            </tr>
	                            <tr id="rowDelivery">
	                                <td>
	                                    <span id="lblDelivery" class="ThemeHeader">Report Delivery:</span>
	                                    <br>

	                                    <input type="radio" row="1" name="inputReportDelivery" id="inputReportDelivery_1" value="html" checked="True"><label for="inputReportDelivery_{position()}">HTML</label>&nbsp;
	                                    <input type="radio" row="2" name="inputReportDelivery" id="inputReportDelivery_2" value="Attachment"><label for="inputReportDelivery_{position()}">Attachment</label>&nbsp;
	                                    <br>
	                                    <br>
	                                </td>
	                            </tr>
	                            <tr id="rowSubject">
	                                <td><span id="lblSubject">Email Subject:</span>
	                                    <br><span><input type="TEXT" value="User Registration" size="50" id="inputEmailSubject" name="inputEmailSubject" class="rdThemeInput"></span>
	                                    <br>
	                                    <br>
	                                </td>
	                            </tr>
	                            <tr id="rowBody">
	                                <td><span id="lblBody">Email Body:</span>
	                                    <br><span><textarea rows="3" cols="50" id="inputBody" name="inputBody" class="rdThemeInput">Your requested report is attached.</textarea></span>
	                                    <br>
	                                    <br>
	                                </td>
	                            </tr>
	                            <tr id="rowSubmit">
	                                <td>
	                                    <input type="button" value="Email Report" id="btnSendEmail" name="btnSendEmail">
	                                </td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </span>
	            </div>






	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	      </div>
	      
	    </div>
	  </div>
	  
	</div>
	<script type="text/javascript">
		$("document").ready(function(){ 
        	$(".btn-inverse").trigger('click');
		});
	</script>
	</body>
</html>
	<?php 



}
?>