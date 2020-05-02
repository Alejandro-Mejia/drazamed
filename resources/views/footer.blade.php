
        <nav>
            <div class="col-md-5">
                <ul class="nav nav-pills pull-left">

                    <li><a href="#">Politica de privacidad</a></li>
                    <li><a href="#" class="register-btn">Terminos y Condiciones</a></li>
                    <li><a href="{{ URL::to('help-desk') }}">Ayuda</a></li>
                    <div class="clear"></div>

                </ul>
            </div>
            <div class="col-md-3 footer-logo">
                <!-- {{ config('SYSTEM_IMAGE_URL')  }} -->
                <img height="30" width="129" src="{{  config('constants.SYSTEM_IMAGE_URL') . Setting::param('site','logo')['value'] }}" alt="{{ Setting::param('site','app_name')['value']  }}">
            </div>
            <div class="col-md-4 copyright">
                <p>Derechos reservados@ {{strtolower(Setting::param('site','app_name')['value']) }} <?php echo date('Y') ?>.</p>

            </div>
            <div class="clear"></div>
        </nav>
    </div>
</footer>
</div>
<!--page-content-wrapper-->
</div>
<!-- wrapper -->
        <!-- Login Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
<!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="myModalLabel">{{ __('Hey, please login!')}}</h4>
                    </div>
                     <div class="alert alert-danger login_msg" style="display: none; text-align: center"></div>
                    <div class="modal-body user_activate ">


                        {{ Form::open(array('url' => '','class'=>'form-horizontal addPersonal form-group-sm form-control','role'=>'form' ,'id' =>'login_form' )) }}
                            <div class="login-fields">
                                <label class="control-label" for="Address">{{ __('Email Id')}}</label>
                                <div class="">
                                   {{ Form::text('email', Request::old('email'), array('placeholder' => 'awesome@awesome.com','class'=>'form-control login_mail')) }}
                                </div>
                                <p style="display: none;" id="login_name_error"></p>
                            </div>

                            <div class="login-fields">
                                <label class="control-label" for="Address">{{ __('Password')}}</label>
                                <div class="">
                                   {{ Form::password('password',array('placeholder' => '*******','class'=>'form-control login_pass')) }}
                                </div>
                                <p style="display: none;" id="login_pwd_error"></p>
                            </div>

                            <div class="clear"></div>
                                <div class="login-btn text-center">
                                    {{ Form::button(__('Login'),['class'=>'btn btn-primary save-btn ripple','style'=>'float:none','onclick'=>'login()']) }}
                                    {{--{{ Form::submit('Login',array('class'=>'btn btn-primary save-btn ripple','data-color'=>'#82DCDF') )  }}--}}
                                    <div class="forgotPwd"><a href="#" id="changePwd">{{ __('Forgot Password?')}}</a></div>
                                    <div class="clear"></div>
                                </div>
                            <div class="clear"></div>
                        {{ Form::close() }}



                    </div>
                    <div class="modal-footer">
<!--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--                        <button type="button" class="btn btn-primary">Save changes</button>-->
                        <p class="login-footer">{{ __('Don’t have an account?')}} <a href="#" id="regModal">{{ __('Sign up here')}}</a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="myModalLabel">{{ __('Welcome to')}}  {{ Setting::param('site','app_name')['value'] }}</h4>
                    </div>
                    <div class="modal-body">
                    <div class="alert alert-success success_msg" style="display: none;"></div>
                                        <div class="alert alert-danger failure_msg" style="display: none;"></div>

                        <form class="form-horizontal addPersonal form-group-sm form-control" role="form" id="user_reg">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="login-fields">
                                <label class="control-label" for="Address">{{__('First Name')}}</label>
                                <div class="">
                                    <input class="form-control" type="text" placeholder="{{ __('Enter your Name') }}" name="first_name" id="user_name">
                                </div>
                                <p style="display: none;" id="user_name_error"></p>
                            </div>
                            <div class="login-fields">
                                <label class="control-label" for="Address">{{__('Last Name')}}</label>
                                <div class="">
                                    <input class="form-control" type="text" placeholder="{{ __('Enter your Last Name')}}" name="last_name" id="user_name">
                                </div>
                                <p style="display: none;" id="user_name_error"></p>
                            </div>
                            <div class="login-fields">
                                <label class="control-label" for="Address">{{ __('Email Id')}}</label>
                                <div class="">
                                    <input class="form-control" type="text" placeholder="yourname@email.com" name="email" id="email" onblur="this.CheckUsername(this.value)" onchange="CheckUsername(this.value)">
                                </div>
                                <p style="display: none;" id="user_mail_error"></p>
                            </div>
                            <div class="login-fields">
                                <label class="control-label" for="mobile">{{ __('Mobile No')}}:</label>
                                <div class="">
                                    <input class="form-control" type="text" placeholder="+57" name="phone">
                                </div>
                            </div>

                             <div class="login-fields">
                               <label for="sel1">{{__('Select type')}}:</label>
                               <select class="form-control" id="sel1" name="user_type">
                                 <option value="0">{{_('Select')}}</option>
                                 <?php
                                    $user_type = UserType::users();

                                    foreach($user_type as $key => $type){
                                        if($type != UserType::ADMIN()){
                                            if($type != UserType::CUSTOMER()){
                                                echo "<option value='$type'>$key UserType::CUSTOMER()</option>";
                                            } else {
                                                echo "<option value='$type' selected>$key</option>";
                                            }
                                        }
                                    }
                                 ?>

                               </select>
                               <p style="display: none;" id="user_type_error"></p>
                             </div>


                            <div class="login-fields">
                                <label class="control-label" for="password">{{ __('Password')}}</label>
                                <div class="">
                                    <input class="form-control paswd" type="password" placeholder="*******" name="password">
                                </div>
                                <p style="display: none;" id="user_pass_error"></p>
                            </div>

                            <div class="login-fields">
                                <label class="control-label" for="address">{{ __('Address')}}</label>
                                <div class="">
                                    <textarea class="form-control" name="address" id="reg_address" style="resize: none;"></textarea>
                                </div>
                                <p style="display: none;" id="user_address_error"></p>
                            </div>
                            <div class="login-fields">
                                <input type="checkbox" class="checkbox accept_terms" id="agree" checked="true" /> <label>{{ __('I am above 18 years of age and I accept all terms and conditions')}}</label>
                            </div>
                            <p style="display: none;" id="user_agree_error"></p>
                            <div class="clear"></div>
                            <div class="signup-btn">
                                <button type="button" class="btn btn-primary save-btn ripple" id="register"  data-color="#82DCDF" onclick="user_register();">{{ __('SIGN UP')}}</button>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
<!--<script src="javascripts/jquery-1.11.2.min.js"></script>-->

<!-- Forgot Password Model -->
    <div class="modal fade" id="myModal_forgot_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="myModalLabel">{{ __('Welcome to')}} {{ Setting::param('site','app_name')['value'] }}</h4>
                    </div>
                    <div class="modal-body">
                    <div class="alert alert-success change_pass_msg" style="display: none;"></div>
                    <div class="alert alert-success success_msg" style="display: none;"></div>
                        <form class="form-horizontal addPersonal form-group-sm form-control" role="form" id="forgot_password">

                            <div class="login-fields">
                                <label class="control-label" for="Address">{{ __('Email')}}</label>
                                <div class="">
                                    <input class="form-control" type="email" placeholder="Email Id" name="email" id="forgot_email" onblur="">
                                </div>
                                <p style="display: none;" id="user_reset_mail_error"></p>
                            </div>

                            <div class="clear"></div>
                            <div class="signup-btn">
                                <button type="button" class="btn btn-primary ripple" id="forgot_pass"  data-color="#82DCDF" style="background-color: #05D7A8" onclick="forgot_password();">{{ __('Save changes')}}</button>
                                <div class="clear"></div>
                            </div>

                            <div class="cancel-btn">
                                <button type="button" class="btn ripple" id="forgot_pass1"  data-color="#82DCDF" onclick="$('#myModal_forgot_password').hide();">{{ __('Cancel')}}</button>
                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
<!-- Forgot Password Model
<!--password change model-->

        <div class="modal fade" id="myModal_change_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="myModalLabel">{{ __('Welcome to')}} {{ Setting::param('site','app_name')['value'] }}</h4>
                    </div>
                    <div class="modal-body">
                    <div class="alert alert-success change_pass_msg" style="display: none;"></div>
                    <div class="alert alert-success success_msg" style="display: none;"></div>
                    <div class="alert alert-danger failure_msg" style="display: none;"></div>
                    <h4 style="text-align: center">{{ __('Reset Password')}}</h4>
                        <form class="form-horizontal addPersonal form-group-sm form-control" role="form" id="change_password">

                            <div class="login-fields">
                                <label class="control-label" for="Address">{{ __('Email')}} </label>
                                <div class="">
                                    <input class="form-control" type="text" placeholder="Email ID " name="email" id="change_email" onblur="" />
                                    <input class="form-control" type="hidden" placeholder="token" name="security_token" id="security_token" value="<?php echo isset($_GET['reset_code']) ? $_GET['reset_code'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="login-fields">
                                <label class="control-label" for="mobile">{{ __('New password')}}</label>
                                <div class="">
                                    <input class="form-control" type="password" placeholder="New password" name="new_password" id="new_password">
                                </div>
                            </div>

                            <div class="login-fields">
                                <label class="control-label" for="password">{{ __('Re-enter Password')}}</label>
                                <div class="">
                                    <input class="form-control paswd" type="password" placeholder="Re-enter password" name="re_password" id="re_password">
                                </div>
                                <p style="display: none;" id="user_pass_error"></p>
                            </div>

                            <div class="clear"></div>
                            <div class="signup-btn">
                                <button type="button" class="btn btn-primary ripple" id="change_pass"  data-color="#82DCDF" style="background-color: #05D7A8" onclick="change_password();">{{ __('Save changes')}}</button>
                                <div class="clear"></div>
                            </div>

                            <div class="cancel-btn">
                                <button type="button" class="btn ripple" id="change_pass"  data-color="#82DCDF" onclick="$('#myModal_change_password').hide();">{{ __('Cancel')}}</button>
                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </form>

                    </div>
                </div>
            </div>
        </div>





<script src="{{URL::asset('/assets/javascripts/jquery.ripple.js')}}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script>
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

        $( "#email-input-reg" ).blur(function() {
            alert( "Handler for .blur() called." );
        });

        /* ripple effect */
//        ( function( $ ) {
//            $( function() {
//                $( '.ripple' ).ripple();
//                $( '.ripple-fast' ).ripple( { 'v_duration': 150, 'h_duration': 150 } );
//                $( '.ripple-slow' ).ripple( { 'v_duration': 1000, 'h_duration': 1000 } );
//                $( '.ripple-fast-slow' ).ripple( { 'v_duration': 600, 'h_duration': 150 } );
//
//            } );
//        } )( jQuery );
    });


    function user_register()
    {
        var last_type=$("#sel1").val();
        var first_type=$("#sel1").val();
        var pass=$('.paswd').val();
        var email=$('#email').val();
        var first_name=$('#first_name').val();
        var last_name=$('#last_name').val();
        var user_type=$('#user_type').val();
        var address = $('#reg_address').val();

        console.log ("UserType:" + user_type);

         if(last_name=="")
          {
           $("#last_name_error").css({"display":"block", "color":"red"});
           $("#last_name_error").html('Please enter your last name');
           return false;
          }

        if(first_name=="")
          {
           $("#first_name_error").css({"display":"block", "color":"red"});
           $("#first_name_error").html('Please enter your first name');
           return false;
          }

        if(email=="")
          {
          $("#user_name_error").hide();
           $("#user_mail_error").css({"display":"block", "color":"red"});
           $("#user_mail_error").html('Please enter your email');
           return false;
          }
        if(user_type==0)
          {
          $("#first_name_error").hide();
          $("#last_name_error").hide();
          $("#user_mail_error").hide();
           $("#user_type_error").css({"display":"block", "color":"red"});
           $("#user_type_error").html('Please choose a type');
           return false;
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

        if(address == ""){
            $('#user_address_error').css({'color':'red'}).html('Field is required').show();
            return false;
        }
          $.ajax({
            type: "POST",
            url: '{{ URL::to('user/create-user/1' )}}',
            data: $( "#user_reg" ).serialize(),
            datatype: 'json',

            done: function (data) {
                    console.log(textStatus + ": " + jqXHR.status);
            	    $(".success_msg").css({"display":"block"});
                    $(".success_msg").html('Successfully registered...<br> Please check your mail, we will send a secret code');
                    $(".success_msg").delay(10000).fadeOut("slow");
                    location.reload();
            },
            fail: function(jqXHR, textStatus, errorThrown){
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

          url: '{{ URL::to('user/check-user-name')}}',
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
        console.log ('pwd:' + pwd);

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
            url: '{{ URL::to('user/user-login/1')}}',
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
                        location.href="{{ URl::to('/').'/account-page' }}"
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
            url: '{{ URL::to('user/activate-account')}}',
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
        url: '{{ URL::to('user/reset-password') }}',
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
    url:'{{ URL::to('user/reset-password') }}',
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

</script>
</body>
</html>