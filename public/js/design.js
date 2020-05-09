var current_item_code="";

$(document).ready(function(){

        var token = $('#security_token').val();


        if(token != "" || token != 0){
            $('#myModal_change_password').modal({});
        }
        $('.login_mail').keyup(function(e){
            if($(this).val() == ""){
                $('#login_name_error').show();
            }else{
                $('#login_name_error').hide();
            }

        });
        $('.cart_file_input').change(function(e){
            $file_name = $(this).val();
            $('.file-upload p').html($file_name);
        })
        /* menu toggle */
        $(".menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });

        $('.menu-toggle').click(function(e){
            var i;
            if($('.sidebar-nav li').eq(1).hasClass('fadeInUp-1')){
                //$('.menu-toggle').removeClass('menu-cross');
                for(i=0;i<6;i++){

                    $('.sidebar-nav li').eq(i).removeClass('fadeInUp-'+i);
                }
            }
            else{
                //$('.menu-toggle').addClass('menu-cross');
                for(i=0;i<6;i++){
                    $('.sidebar-nav li').eq(i).addClass('fadeInUp-'+i);
                }
            }

        });

        $('#loginModal,.loginModal').click(function(){
            $('#myModal').modal({
                //keyboard: false
            });
        });

        $('#regModal,.regModal').click(function(){
            $('#registerModal').modal({
               //keyboard: false
            });
        });
        $('.regModal1').click(function(){
            $('#registerModal').modal({
               //keyboard: false
            });
            $('#myModal').hide();
        });
        $('#changePwd').click(function(){
            $('#myModal_forgot_password').modal({
               //keyboard: false
            });
        });

        $( "#email_input_reg" ).blur(function() {
        	CheckUsername(this.value);
        });

		$("#search_medicine").autocomplete({
		    search: function(event, ui) {
		        $('.med_search_loader').css('display','block' );
		    },
		    open: function(event, ui) {
		        $('.med_search_loader').css('display','none' );
		    },
		    source: '/medicine/load-medicine-web/1',
		    minLength: 0,
		    delay: 0 ,

	        response: function( event, ui ) {
	         $('.med_search_loader').css('display','none' );
	        },

            select: function (event, ui) {
                    console.log("itemCode="+ui.item.item_code);
                    item_code = ui.item.item_code;
                    current_item_code=item_code;
                    goto_detail_page();
            },
		}).autocomplete( "instance" )._renderItem = function(ul, item ) {
            return $( "<li>" )
                .append( "<div>" + item.label + "</div>" )
                .appendTo( ul );
        }


    });




    function user_register()
    {
        var last_type=$("#sel1").val();
        var first_type=$("#sel1").val();
        var pass=$('#pwd_input_reg').val();
        var pass_conf=$('#pwdconf_input_reg').val();
        var email=$('#email_input_reg').val();
        var first_name=$('#first_name').val();
        var last_name=$('#last_name').val();
        var user_type=$('#user_type').val();
        var address = $('#address_input').val();
        var phone = $('#phone_input').val();

        console.log ("UserType:" + user_type);

        if(first_name=="")
		{
			$("#first_name_error").css({"display":"block", "color":"red"});
			$("#first_name_error").html('Please enter your first name');
			return false;
		} else {
			$("#first_name_error").css({"display":"none"});
		}

		if(last_name=="")
		{
			$("#last_name_error").css({"display":"block", "color":"red"});
			$("#last_name_error").html('Please enter your last name');
			return false;
		} else {
			$("#last_name_error").css({"display":"none"});
		}

		if(phone=="")
		{
			$("#phone_error").css({"display":"block", "color":"red"});
			$("#phone_error").html('Please enter your phone number');
			return false;
		} else {
			$("#phone_error").css({"display":"none"});
		}

		if(user_type==0)
		{
			$("#first_name_error").hide();
			$("#last_name_error").hide();
			$("#user_mail_error").hide();
			$("#user_type_error").css({"display":"block", "color":"red"});
			$("#user_type_error").html('Please choose a type');
			return false;
		} else {
			$("#user_type_error").css({"display":"none"});
		}

		if(address == ""){
            $('#user_address_error').css({'color':'red'}).html('Field is required').show();
            return false;
        } else {
			$("#user_address_error").css({"display":"none"});
		}

		if(email=="")
		{
			$("#user_name_error").hide();
			$("#user_mail_error").css({"display":"block", "color":"red"});
			$("#user_mail_error").html('Please enter your email');
			return false;
		} else {
			$("#user_mail_error").css({"display":"none"});
		}

        if(pass.length<=0)
        {
			$("#first_name_error").hide();
			$("#last_name_error").hide();
			$("#user_mail_error").hide();
			$("#user_type_error").hide();
			$("#user_pass_error").css({"display":"block", "color":"red"});
			$("#user_pass_error").html('Please Enter a password');
			return false;
        } else {
			$("#user_pass_error").css({"display":"none"});
		}

		if( !(pass == pass_conf))
        {
			$("#user_passcnf_error").css({"display":"block", "color":"red"});
			$("#user_passcnf_error").html('Passwords are different');
			return false;
        } else {
			$("#user_passcnf_error").css({"display":"none"});
		}


		if($("#agree").is(':checked')){
			$("#first_name_error").hide();
			$("#last_name_error").hide();
			$("#user_mail_error").hide();
			$("#user_type_error").hide();
			$("#user_pass_error").hide();
			$("#user_agree_error").hide();
		}else{
			$("#first_name_error").hide();
			$("#last_name_error").hide();
			$("#user_mail_error").hide();
			$("#user_type_error").hide();
			$("#user_pass_error").hide();
			$("#user_agree_error").css({"display":"block", "color":"red"});
			$("#user_agree_error").html('Accept the terms & conditions to proceed');
			return false;
		}


          $.ajax({
            type: "POST",
            url: 'user/create-user/1',
            data: $( "#user_reg" ).serialize(),
            datatype: 'json',

            success: function (data) {
            	    $(".success_msg").css({"display":"block"});
                    $(".success_msg").html('Successfully registered...<br> Please check your mail, we will send a secret code');
                    $(".success_msg").delay(10000).fadeOut("slow");
                    location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
            },
            statusCode: {
                500:function(data){
                    $(".failure_msg").css({"display":"block"});
                    $(".failure_msg").html('Oops ! Some Technical Failure has occured');
                    $(".failure_msg").delay(10000).fadeOut("slow");
                },
                401:function(data){
                    $(".failure_msg").css({"display":"block"});
                    $(".failure_msg").html('Sorry ! Invalid request made to server');
                    $(".failure_msg").delay(10000).fadeOut("slow");
                },
                409:function(data){
                    $(".failure_msg").css({"display":"block"});
                    $(".failure_msg").html('Sorry we could not register.. User Already Added to System');
                    $(".failure_msg").delay(10000).fadeOut("slow");
                }
            },
          });


    }

    /**
    * CHeck User Name
    */
    function CheckUsername(u_name)
    {
        $.ajax({
          type: "GET",

          url: '/user/check-user-name',
          data: "u_name="+u_name,
          datatype: 'json',
          statusCode:{
            400:function(data){
                $("#user_mail_error").css({"display":"block", "color":"red"});
                $("#user_mail_error").html('Enter a valid email');
                $('#register').attr("disabled", true);
            },
            409:function(data){

                $("#user_mail_error").css({"display":"block", "color":"red"});
                $("#user_mail_error").html('Email already exist');
                $('#register').attr("disabled", true);
            }
          },
          success: function (data) {
          	    $("#user_mail_error").css({"display":"block", "color":"green"});
                $("#user_mail_error").html('Valid Email');
                $('#register').attr("disabled", false);

          }
        });
    }


// $('#searchButton').on('click', goto_detail_page());




/**
* Change User Selection
*/
$("#sel1").change(function () {
		var user_type = $("#sel1").val();
		if (user_type == 3) {
			$("#user_type_error").css({"display": "block", "color": "green"});
			$("#user_type_error").html('You chosen as CUSTOMER type');
		}
		else {
			$("#user_type_error").css({"display": "block", "color": "green"});
			$("#user_type_error").html('You chosen as MEDICAL PRACTITIONER type');
		}
	});

/**
 * Login Functionality
 */
function login()
{
    $("#login_name_error").hide();
    $("#login_pwd_error").hide();
    var activate_form = "";
    var uname=$(".login_mail").val();
    var pwd=$('.login_pass').val();

    console.log ('UName:' + uname);
    console.log ('UPwd:' + pwd);

    if(uname=="")
    {
        $("#login_name_error").css({"display":"block", "color":"red"});
        $("#login_name_error").html('Please enter user name');
        return false;
    }
    if(pwd=="")
    {
        $("#login_name_error").hide();
        $("#login_pwd_error").css({"display":"block", "color":"red"});
        $("#login_pwd_error").html('Please enter password');
        return false;
    }else{
        $("#login_name_error").hide();
        $("#login_pwd_error").hide();
    }
  	$.ajax({
        type: "POST",
        url: '/user/user-login/1',
        data: $( "#login_form" ).serialize(),
        datatype: 'json',
        complete:function(data){

        },
        statusCode:{
            403:function(data){
               $(".login_msg").html('Please Login from Admin URL');
                $(".login_msg").css({"display":"block"});
                $(".login_msg").delay(5000).fadeOut("slow");
            }
        },
        success: function (data) {
	        var status=data[0].result.status;
	        var page=data[0].result.page;

            if(status=='pending')
            {
                var mail=$('.login_mail').val();

                 $(".login_msg").html('Please activate your account');
                 $(".login_msg").css({"display":"block"});
                 $(".login_msg").delay(5000).fadeOut("slow");

                 activate_form +='<input type="hidden" id="hidden_user_id" value="'+mail+'">';

                 activate_form +=' <div class="login-fields">';
                 activate_form +='<label class="control-label" for="Address">Enter Your activation code</label>';
                 activate_form +=' <div class=""> <input class="form-control" type="text" id="activation_code" placeholder="Enter your Activation Code" name="user_name"> </div> </div>';
                 activate_form +='<div class="signup-btn">';
                 activate_form +='<button type="button" class="btn btn-primary save-btn ripple" id="register"  data-color="#82DCDF" onclick="activate_user();">ACTIVATE</button>';
                 activate_form +=' <div class="clear"></div> </div>';


                 $(".user_activate").html(activate_form);
            }
             if(status=='failure')
            {
            $(".login_msg").html('Invalid username or password');
            $(".login_msg").css({"display":"block"});
            $(".login_msg").delay(5000).fadeOut("slow");

            }
            if(status=='success')
            {
                if(page=='no')
                    location.href="/account-page"
                else
                    location.href='../medicine/add-cart/1';
            }

            if(status == 'delete'){
             $(".login_msg").html('You have been deleted by admin ! Contact support team.');
                            $(".login_msg").css({"display":"block"});
                            $(".login_msg").delay(5000).fadeOut("slow");
            }

        }
      });


}
function activate_user()
{
var activation_code=$('#activation_code').val();
var login_mail=$('#hidden_user_id').val();


$.ajax({
            type: "POST",
            url: '/user/activate-account',
            data: 'email='+login_mail+'&security_code='+activation_code,
            datatype: 'json',
            error:function (xhr, ajaxOptions, thrownError){
                    $(".login_msg").html('Sorry...Activation failed! ');
                    $(".login_msg").css({"display":"block"});
                    $(".login_msg").delay(5000).fadeOut("slow");
            },
            success: function (data) {
                    $(".login_msg").html('Your account successfully activated ');
                    $(".login_msg").css({"display":"block"});
                    $(".login_msg").delay(5000).fadeOut("slow");
                    location.reload();
            }
          });

}

    /**
     * Reset Forgot Password
     */
function forgot_password(){

    if(!$('#forgot_email').val()){
        $('#user_reset_mail_error').css({"display":"block", "color":"red"}).html('Please enter the email');
    }else{
        $('#user_reset_mail_error').css({"display":"none"});
    }
    $.ajax({
        url: '/user/reset-password',
        data:$('#forgot_password').serialize(),
        type:'POST',
        dataType:'JSON',
        statusCode:{
            404:function(data){
               $('#user_reset_mail_error').css({"display":"block", "color":"red"}).html('No User Found !');
            }
        },
        success:function(data){
            $('#user_reset_mail_error').css({"display":"block", "color":"green"}).html('Please check your email for the reset link  !');
        }
    })
}
function change_password()
{
var email=$('#change_email').val();
var token = $("#security_token").val();
var new_password=$('#new_password').val();
var re_password=$('#re_password').val();
if(email=="")
{
    $(".change_pass_msg").css({"display":"block", "color":"red"});
    $(".change_pass_msg").html('Please enter old password');
    return false;
}
if(new_password=="")
{
    $(".change_pass_msg").css({"display":"block", "color":"red"});
    $(".change_pass_msg").html('Please enter new password');
    return false;
}
if(re_password=="")
{
    $(".change_pass_msg").css({"display":"block", "color":"red"});
    $(".change_pass_msg").html('Please confirm new password');
    return false;
}
 if(new_password==re_password)
 {
  $.ajax({
    type:"POST",
    url:'/user/reset-password',
    data:"new_password="+new_password+"&re_password="+re_password+"&email="+email+"&security_code="+token,
    dataType:'JSON',
    statusCode:{
        401:function(data){
                $(".change_pass_msg").css({"display":"block", "color":"red"});
                $(".change_pass_msg").html('Invalid user details !');
        }
    },
    success:function(data){
             $(".change_pass_msg").css({"display":"block", "color":"green"});
             $(".change_pass_msg").html('Your passowrd has successfully changed, Please Log in with the new password');
             setTimeout(function(e){
             $('#myModal_change_password').modal('hide');
             $('#myModal').modal('show');
             },2000);
    }

  });

 }
 else
 {

  $(".change_pass_msg").html('Sorry...Password not matching! ');
  $(".change_pass_msg").css({"display":"block"});
  $(".change_pass_msg").delay(5000).fadeOut("slow");

 }
}

function goto_detail_page()
{
    var name=$(".search_medicine").val();
    if(current_item_code=="" && name!="")
    {
        $.ajax({
        url: 'medicine/add-new-medicine',
        data: 'name='+name,
        type: 'POST',
        datatype: 'JSON',
        success: function (data) {
                if(data.status)
                {
                    $('#new_med_status').show();
                    $('#new_med_status').addClass('alert-success');
                    $('#new_med_status').html('This medicine is not available for now. Please check availability later.');
                    $("#new_med_status").delay(5000).fadeOut("slow");
                }else
                {
                    $('#new_med_status').show();
                    $('#new_med_status').addClass('alert-danger');
                    $('#new_med_status').html('Something went wrong. Please try again later.');
                    $("#new_med_status").delay(5000).fadeOut("slow");
                }
            }
        });
    }
    else{
        window.location="medicine-detail/"+current_item_code;

    }
}