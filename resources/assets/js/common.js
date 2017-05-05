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
            seller_name: {
                required    : false,
                maxlength   : 100,
            },
            shop_name: {
                required    : true,
                maxlength   : 100,
            },
            phone_number: {
                required    : true,
                maxlength   : 12,
                minlength   : 8,
                digits      : true
            },
            email: {
                required    : false,
                email       : true,
            },           
            shop_address    : {
                required    : false,
                minlength   : 3,
                maxlength   : 100,
            },
            shop_zipcode:  {
                required    : false,
                maxlength   : 5,
            },            
            shop_start_time : {
                required    : false,
                minlength   : 3,
                maxlength   : 8,
            },
            shop_close_time : {
                required    : false,
                minlength   : 3,
                maxlength   : 8,
            },
            shop_document   : {
                required    : false,
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



