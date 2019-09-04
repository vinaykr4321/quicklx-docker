$(document).ready(function () {
    // Hide the header on the index page
    $('header .card').css({"display": "none"});

    // Cal lto ajax to create options for courses multi-select
    $('#id_company').click(function () {
        var companyid = [];
        $('#id_company option').each(function ()
        {
            if (this.selected == true) {
                companyid.push(this.value);
            }
        });
        $.ajax({
            url: 'courseoption.php',
            data: {'companyid': JSON.stringify(companyid)},
            type: 'POST',
            success: function (data) {
                $("#id_course option").remove();
                $('#id_course').append(data);
                $("#id_course").after("<a href='http://localhost/mod/certificate/all_certificates.zip' id='download' style='display:none;' ></a>");
            }
        });
    });

});
