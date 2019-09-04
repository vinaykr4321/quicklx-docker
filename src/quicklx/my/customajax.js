/* This file is part of Moodle - http://moodle.org/

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//
 * My Moodle -- a user's personal dashboard
 *
 * - Support display of courses by status on dashboard
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  Syllametrics (support@syllametrics.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$(document).ready(function()
{

	
$('figcaption').each(function(){
	var course_link = $(this).find('a').attr('href');
	$(this).find('a').attr('href','#');
	$(this).parent().attr('data-courselink',course_link);
});
$('figure').each(function(){$(this).attr('onclick','');});
$('figcaption').each(function(){$(this).attr('onclick','');});
$('figure').each(function(){$(this).find('a').attr('href','#');});

$('figcaption').off('click');$('figcaption').unbind('click');$('figure').click(function(){
	var dis = $(this);
	var course_link = dis.attr('data-courselink');


	$.ajax
		({
			url: "get_course_exp_info.php",
			type: "GET",
			data: {action: "get_courseid", couserurl: course_link},
			success: function(data)
			{ 
				//alert(data);
				var data=$.parseJSON(data);
				//alert(data.course_id);
				
			    if(data.course_id != 0)
			    {
					if ($("#myModalRegister_start").length < 1){
					$("body").append('<div class="modal fade" id="myModalRegister_start" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>\
                    <h4 class="modal-title">Course Access Expired</h4>\
                            </div>\
                            <div class="modal-body">\
                                <p>Your access to this course is currently expired. If you previously completed the course, you can check your grades and get your certificate below:</p>\
                             </div>\
                            <div class="modal-footer">\
                             <a class="btn btn-primary" href="#" id="get-grade-modal" style="display:none;">Get Grades</a>\
                             <a class="btn btn-primary" href="#" id="get-certificate-modal" style="display:none;">Get Certificate</a>\
                             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
                            </div>\
                      </div>\
                    </div>\
                </div>');
					$("#myModalRegister_start").modal('show');
					}
					else{
						$('#get-certificate-modal').css({"display":"none"});
						$('#get-grade-modal').css({"display":"none"});
						$("#myModalRegister_start").modal('show');
					}
					if(data.course_access){
						$('#get-grade-modal').attr('href',data.course_access);
						$('#get-grade-modal').css({"display":"block"});
					}
					if(data.course_certificate){
						$('#get-certificate-modal').attr('href',data.course_certificate);
						$('#get-certificate-modal').css({"display":"block"});						
					}					
					//check++;
					
				
			 	}	
			 	else{
			 		window.location = course_link;
			 	}
			 	
			},
		//e.preventDefault();
	   		error: function(err){console.log("Unexpected error retrieving course expiration information.");}
		}); 	

	});
});
