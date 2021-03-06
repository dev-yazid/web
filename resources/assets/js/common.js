$(document).ready(function() {
    $('#user').validate({
         rules: {
            name: "required",           
            password: {
                required    : true,
                minlength   : 6,
                maxlength   : 25,
            },            
            confirmpassword : {
                required    : true,
                minlength   : 6,
                equalTo     : "#password"
            },
            shop_mobile: {
                required    : false,
                maxlength   : 10,
                minlength   : 10,
                digits      : true
            },
            email: {
                required    : true,
                email       : true,
            },
            shop_name       : "required",
            shop_address    : {
                required    : true,
                minlength   : 3,
                maxlength   : 120,
            },
            shop_city   : "required",
            shop_zipcode: "required",
            shop_gps_lat    : {
                required    : false,
                minlength   : 3,
            },
            shop_gps_long   : {
                required    : false,
                minlength   : 3,
                maxlength   : 60,
            },
            shop_start_time : {
                required    : false,
                minlength   : 3,
                maxlength   : 60,
            },
            shop_close_time : {
                required    : false,
                minlength   : 3,
                maxlength   : 10,
            },
            shop_document   : {
                required    : true,
            },
        },
    });

    $('#product').validate({
        rules: {
            brand  : "required",
            pname  : {
                required    : true,
                minlength   : 3,
                maxlength   : 60,
            },
            year   : {
                required    : true,
            },
            status : "required",
        },
    });

    $('#city').validate({
        rules: {
            name   : {
                required    : true,
                minlength   : 3,
                maxlength   : 60,
            },
            status : "required",
        },
    });

    $('#brand').validate({
        rules: {
            brand  : {
                required    : true,
                minlength   : 3,
                maxlength   : 60,
            },
            status : "required",
            image  : "required",
        },
    });
    
    
$(document).ready(function() {
    /* date picker */
    $(".datepicker").datepicker({ 
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
        });
    });

    /* select2 */
    $(".select2").select2();

    $(".passwordFields #password").val("");
    $(".passwordFields #confirmpassword").val("");

    $(document).ready(function() {
        var changePassword = $('#update_password').val();            
        if(changePassword == "No")
        {
            $(".passwordFields").hide()
        }
        $('#update_password').change(function(){
            var changePassword = $(this).val();
            if(changePassword == "Yes")
            {
                $(".passwordFields").show("fade", {}, 300);
            }
            else
            {
                $(".passwordFields").hide("fade", {}, 300);
            }
        })
    });

    $('.alert').delay(15000).fadeOut('slow');
      
});



