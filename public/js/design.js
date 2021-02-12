var current_item_code = "";
var categories = [];
var ulclick;
var element;
var archivos = false;

var translate = {
    activate_account: "@lang('Please activate your account')",
    enter_user_name: "trans('Please enter user name')",
    enter_password: "trans('Please enter password')",
    login_admin: "trans('Please Login from Admin URL')",
    enter_code: "trans('Enter your Activation Code')",
    enter_code_p: "trans('Enter your activation code')",
    invalid_login: "trans('Invalid username or password')",
    deleted_by_admin:
        "trans('You have been deleted by admin ! Contact support team.')",
    activation_failed: "trans('Sorry...Activation failed!')",
    activation_success: "trans('Your account successfully activated')",
    enter_email: "trans('Please enter the email')",
    user_not_found: "trans('No User Found !')",
    check_email: "trans('Please check your email for the reset link!')",
    old_password: "trans('Please enter old password')",
    new_password: "trans('Please enter new password')",
    confirm_password: "trans('Please confirm new password')",
    invalid_user: "trans('Invalid user details !')",
    password_changed:
        "trans('Your passowrd has successfully changed, Please Log in with the new password')",
    password_not_match: "trans('Sorry...Password not matching! ')"
};

// var lang = new Lang({
//     messages: source,
//     locale: 'es',
//     fallback: 'en'
// });

function trans(key, replace = {}) {
    let translation = key
        .split(".")
        .reduce((t, i) => t[i] || null, window.translations);

    for (var placeholder in replace) {
        translation = translation.replace(
            `:${placeholder}`,
            replace[placeholder]
        );
    }

    return translation;
}

//getCategories();
//show_favorites();

$(document).ready(function() {
    var token = $("#security_token").val();



    if (token != "" || token != 0) {
        $("#myModal_change_password").modal({});
    }
    $(".login_mail").keyup(function(e) {
        if ($(this).val() == "") {
            $("#login_name_error").show();
        } else {
            $("#login_name_error").hide();
        }
    });
    $(".cart_file_input").change(function(e) {
        $file_name = $(this).val();
        $(".file-upload p").html($file_name);
    });
    /* menu toggle */
    $(".menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    $(".menu-toggle").click(function(e) {
        var i;
        if (
            $(".sidebar-nav li")
                .eq(1)
                .hasClass("fadeInUp-1")
        ) {
            //$('.menu-toggle').removeClass('menu-cross');
            for (i = 0; i < 6; i++) {
                $(".sidebar-nav li")
                    .eq(i)
                    .removeClass("fadeInUp-" + i);
            }
        } else {
            //$('.menu-toggle').addClass('menu-cross');
            for (i = 0; i < 6; i++) {
                $(".sidebar-nav li")
                    .eq(i)
                    .addClass("fadeInUp-" + i);
            }
        }
    });

    $("#loginModal,.loginModal").click(function() {
        $("#myModal").modal({
            //keyboard: false
        });
    });

    $("#regModal,.regModal").click(function() {
        $("#registerModal").modal({
            //keyboard: false
        });
    });
    $(".regModal1").click(function() {
        $("#registerModal").modal({
            //keyboard: false
        });
        $("#myModal").hide();
    });
    $("#changePwd").click(function() {
        $("#myModal_forgot_password").modal({
            //keyboard: false
        });
    });

    $("#email_input_reg").blur(function() {
            if (($("element").data("bs.modal") || {})._isShown) {
                CheckUsername(this.value);
            }
        });

    // /**
    //  * Busqueda de Medicamentos por nombre
    //  * @param cat= Categoria lab= Laboratorio term= Nombre Medicamento limit= #resultados max
    //  */
    // $("#search_medicine")
    //     .autocomplete({
    //         search: function(event, ui) {
    //             $(".med_search_loader").css("display", "block");
    //         },
    //         open: function(event, ui) {
    //             $(".med_search_loader").css("display", "none");
    //         },
    //         source: "/medicine/load-medicine-web/1",
    //         minLength: 2,
    //         delay: 0,
    //         max: 10,

    //         response: function(event, ui) {
    //             $(".med_search_loader").css("display", "none");
    //         },

    //         select: function(event, ui) {
    //             console.log("itemCode=" + ui.item.item_code);
    //             item_code = ui.item.item_code;
    //             current_item_code = item_code;
    //             // goto_detail_page();
    //             show_detail_modal(ui.item);
    //         }
    //     })
    //     .autocomplete("instance")._renderItem = function(ul, item) {
    //     return $("<li>")
    //         .append("<div>" + item.label + "</div>")
    //         .appendTo(ul);
    //     };


});




/**
 * Registro de un nuevo usuario
 *
 * @return     {boolean}  { registro exitoso }
 */
function user_register() {
    var last_type = $("#sel1").val();
    var first_type = $("#sel1").val();
    var pass = $("#pwd_input_reg").val();
    var pass_conf = $("#pwdconf_input_reg").val();
    var email = $("#email_input_reg").val();
    var first_name = $("#first_name").val();
    var last_name = $("#last_name").val();
    var user_type = $("#user_type").val();
    var address = $("#address_input").val();
    var phone = $("#phone_input").val();
    var agree = $("#agree:checked").length;

    console.log("Agree?:" + agree);

    if (first_name == "") {
        $("#first_name_error").css({ display: "block", color: "red" });
        $("#first_name_error").html("Por favor coloque su nombre");
        return false;
    } else {
        $("#first_name_error").css({ display: "none" });
    }

    if (last_name == "") {
        $("#last_name_error").css({ display: "block", color: "red" });
        $("#last_name_error").html("Por favor coloque su apellido");
        return false;
    } else {
        $("#last_name_error").css({ display: "none" });
    }

    if (phone == "") {
        $("#phone_error").css({ display: "block", color: "red" });
        $("#phone_error").html("Por favor coloque su numero de celular");
        return false;
    } else {
        $("#phone_error").css({ display: "none" });
    }

    if (user_type == 0) {
        $("#first_name_error").hide();
        $("#last_name_error").hide();
        $("#user_mail_error").hide();
        $("#user_type_error").css({ display: "block", color: "red" });
        $("#user_type_error").html("Por favor seleccione un tipo de cliente");
        return false;
    } else {
        $("#user_type_error").css({ display: "none" });
    }

    if (address == "") {
        $("#user_address_error")
            .css({ color: "red" })
            .html("Field is required")
            .show();
        return false;
    } else {
        $("#user_address_error").css({ display: "none" });
    }

    if (email == "") {
        $("#user_name_error").hide();
        $("#user_mail_error").css({ display: "block", color: "red" });
        $("#user_mail_error").html("Por favor coloque un email valido");
        return false;
    } else {
        $("#user_mail_error").css({ display: "none" });
    }

    if (pass.length <= 0) {
        $("#first_name_error").hide();
        $("#last_name_error").hide();
        $("#user_mail_error").hide();
        $("#user_type_error").hide();
        $("#user_pass_error").css({ display: "block", color: "red" });
        $("#user_pass_error").html("Por favor introduzca una contraseña");
        return false;
    } else {
        $("#user_pass_error").css({ display: "none" });
    }

    if (!(pass == pass_conf)) {
        $("#user_passcnf_error").css({ display: "block", color: "red" });
        $("#user_passcnf_error").html("Las contraseñas no coinciden");
        return false;
    } else {
        $("#user_passcnf_error").css({ display: "none" });
    }

    if ($("#agree:checked").length == 1) {
        $("#first_name_error").hide();
        $("#last_name_error").hide();
        $("#user_mail_error").hide();
        $("#user_type_error").hide();
        $("#user_pass_error").hide();
        $("#user_agree_error").hide();
    } else {
        $("#first_name_error").hide();
        $("#last_name_error").hide();
        $("#user_mail_error").hide();
        $("#user_type_error").hide();
        $("#user_pass_error").hide();
        $("#user_agree_error").css({ display: "block", color: "red" });
        $("#user_agree_error").html(
            "Por favor acepte los terminos y condiciones para continuar"
        );
        return false;
    }

    $.ajax({
        type: "POST",
        url: "user/create-user/1",
        data: $("#user_reg").serialize(),
        datatype: "json",

        success: function(data) {
            $(".success_msg").css({ display: "block" });
            $(".success_msg").html(
                "Successfully registered...<br> Please check your mail, we will send a secret code"
            );
            $(".success_msg")
                .delay(10000)
                .fadeOut("slow");

            window.location.href = window.location.href + "?msg=activate";

            //location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
        },
        statusCode: {
            500: function(data) {
                $(".failure_msg").css({ display: "block" });
                $(".failure_msg").html(
                    "Oops ! Some Technical Failure has occured"
                );
                $(".failure_msg")
                    .delay(10000)
                    .fadeOut("slow");
            },
            401: function(data) {
                $(".failure_msg").css({ display: "block" });
                $(".failure_msg").html(
                    "Sorry ! Invalid request made to server"
                );
                $(".failure_msg")
                    .delay(10000)
                    .fadeOut("slow");
            },
            409: function(data) {
                $(".failure_msg").css({ display: "block" });
                $(".failure_msg").html(
                    "Sorry we could not register.. User Already Added to System"
                );
                $(".failure_msg")
                    .delay(10000)
                    .fadeOut("slow");
            }
        }
    });
}

/**
 * CHeck User Name
 */
function CheckUsername(u_name) {
    $.ajax({
        type: "GET",

        url: "/user/check-user-name",
        data: "u_name=" + u_name,
        datatype: "json",
        statusCode: {
            400: function(data) {
                $("#user_mail_error").css({ display: "block", color: "red" });
                $("#user_mail_error").html("Enter a valid email");
                $("#register").attr("disabled", true);
            },
            409: function(data) {
                $("#user_mail_error").css({ display: "block", color: "red" });
                $("#user_mail_error").html("Email already exist");
                $("#register").attr("disabled", true);
            }
        },
        success: function(data) {
            $("#user_mail_error").css({ display: "block", color: "green" });
            $("#user_mail_error").html("Valid Email");
            $("#register").attr("disabled", false);
        }
    });
}

// $('#searchButton').on('click', show_detail_modal());

/**
 * Change User Selection
 */
$("#sel1").change(function() {
    var user_type = $("#sel1").val();
    if (user_type == 3) {
        $("#user_type_error").css({ display: "block", color: "green" });
        $("#user_type_error").html("You chosen as CUSTOMER type");
    } else {
        $("#user_type_error").css({ display: "block", color: "green" });
        $("#user_type_error").html("You chosen as MEDICAL PRACTITIONER type");
    }
});

/**
 * Login Functionality
 */
function login() {
    $("#login_name_error").hide();
    $("#login_pwd_error").hide();
    var activate_form = "";
    var uname = $(".login_mail").val();
    var pwd = $(".login_pass").val();

    // console.log ('UName:' + uname);
    // console.log ('UPwd:' + pwd);

    if (uname == "") {
        $("#login_name_error").css({ display: "block", color: "red" });
        $("#login_name_error").html(translate.enter_user_name);
        return false;
    }
    if (pwd == "") {
        $("#login_name_error").hide();
        $("#login_pwd_error").css({ display: "block", color: "red" });
        $("#login_pwd_error").html(translate.enter_password);
        return false;
    } else {
        $("#login_name_error").hide();
        $("#login_pwd_error").hide();
    }
    $.ajax({
        type: "POST",
        url: "/user/user-login/1",
        data: $("#login_form").serialize(),
        datatype: "json",
        statusCode: {
            403: function(data) {
                $(".login_msg").html("Debe ingresar por la plataforma de administración. <a href='/admin-login' > Redirigir </a>");
                $(".login_msg").css({ display: "block" });
                $(".login_msg")
                    .delay(5000)
                    .fadeOut("slow");
            }
        },
        success: function(data) {
            console.log("Status:" + data);
            var status = data[0].result.status;
            var page = data[0].result.page;

            if (status == "pending") {
                var mail = $(".login_mail").val();

                $(".login_msg").html(
                    "Por favor active su cuenta, le hemos enviado un email con instrucciones"
                );
                $(".login_msg").css({ display: "block" });
                $(".login_msg")
                    .delay(5000)
                    .fadeOut("slow");

                activate_form +=
                    '<input type="hidden" id="hidden_user_id" value="' +
                    mail +
                    '">';

                activate_form += ' <div class="login-fields">';
                activate_form +=
                    '<label class="control-label" for="Address">Enter Your activation code</label>';
                activate_form +=
                    ' <div class=""> <input class="form-control" type="text" id="activation_code" placeholder="Enter your Activation Code" name="user_name"> </div> </div>';
                activate_form += '<div class="signup-btn">';
                activate_form +=
                    '<button type="button" class="btn btn-primary save-btn ripple" id="register"  data-color="#82DCDF" onclick="activate_user();">ACTIVATE</button>';
                activate_form += ' <div class="clear"></div> </div>';

                $(".user_activate").html(activate_form);
            }
            if (status == "failure") {
                $(".login_msg").html("Invalid username or password");
                $(".login_msg").css({ display: "block" });
                $(".login_msg")
                    .delay(5000)
                    .fadeOut("slow");
            }
            if (status == "success") {
                if (page == "no") location.href = "/account-page";
                else location.href = "../medicine/add-cart/1";
            }

            if (status == "delete") {
                $(".login_msg").html(
                    "You have been deleted by admin ! Contact support team."
                );
                $(".login_msg").css({ display: "block" });
                $(".login_msg")
                    .delay(5000)
                    .fadeOut("slow");
            }

            if (status == "redirect"){
                $(".login_msg").html( "Debe ingresar por la consola de administracion");
                $(".login_msg").css({ display: "block" });
                $(".login_msg")
                    .delay(5000)
                    .fadeOut("slow");
                location.href ="/admin-login";
            }
        }
    });
}

/**
 * Activacion de un usuario
 */
function activate_user() {
    var activation_code = $("#activation_code").val();
    var login_mail = $("#hidden_user_id").val();

    $.ajax({
        type: "POST",
        url: "/user/activate-account",
        data: "email=" + login_mail + "&security_code=" + activation_code,
        datatype: "json",
        error: function(xhr, ajaxOptions, thrownError) {
            $(".login_msg").html("Sorry...Activation failed! ");
            $(".login_msg").css({ display: "block" });
            $(".login_msg")
                .delay(5000)
                .fadeOut("slow");
        },
        success: function(data) {
            $(".login_msg").html("Your account successfully activated ");
            $(".login_msg").css({ display: "block" });
            $(".login_msg")
                .delay(5000)
                .fadeOut("slow");
            location.reload();
        }
    });
}

/**
 * Reset Forgot Password
 */
function forgot_password() {
    if (!$("#forgot_email").val()) {
        $("#user_reset_mail_error")
            .css({ display: "block", color: "red" })
            .html("Please enter the email");
    } else {
        $("#user_reset_mail_error").css({ display: "none" });
    }
    $.ajax({
        url: "/user/reset-password",
        data: $("#forgot_password").serialize(),
        type: "POST",
        dataType: "JSON",
        statusCode: {
            404: function(data) {
                $("#user_reset_mail_error")
                    .css({ display: "block", color: "red" })
                    .html("No User Found !");
            }
        },
        success: function(data) {
            $("#user_reset_mail_error")
                .css({ display: "block", color: "green" })
                .html("Please check your email for the reset link  !");
        }
    });
}

/**
 * Change password
 *
 * @return     {boolean}  { description_of_the_return_value }
 */
function change_password() {
    var email = $("#change_email").val();
    var token = $("#security_token").val();
    var new_password = $("#new_password").val();
    var re_password = $("#re_password").val();
    if (email == "") {
        $(".change_pass_msg").css({ display: "block", color: "red" });
        $(".change_pass_msg").html("Please enter old password");
        return false;
    }
    if (new_password == "") {
        $(".change_pass_msg").css({ display: "block", color: "red" });
        $(".change_pass_msg").html("Please enter new password");
        return false;
    }
    if (re_password == "") {
        $(".change_pass_msg").css({ display: "block", color: "red" });
        $(".change_pass_msg").html("Please confirm new password");
        return false;
    }
    if (new_password == re_password) {
        $.ajax({
            type: "POST",
            url: "/user/reset-password",
            data:
                "new_password=" +
                new_password +
                "&re_password=" +
                re_password +
                "&email=" +
                email +
                "&security_code=" +
                token,
            dataType: "JSON",
            statusCode: {
                401: function(data) {
                    $(".change_pass_msg").css({
                        display: "block",
                        color: "red"
                    });
                    $(".change_pass_msg").html("Invalid user details !");
                }
            },
            success: function(data) {
                $(".change_pass_msg").css({ display: "block", color: "green" });
                $(".change_pass_msg").html(
                    "Your passowrd has successfully changed, Please Log in with the new password"
                );
                setTimeout(function(e) {
                    $("#myModal_change_password").modal("hide");
                    $("#myModal").modal("show");
                }, 2000);
            }
        });
    } else {
        $(".change_pass_msg").html("Sorry...Password not matching! ");
        $(".change_pass_msg").css({ display: "block" });
        $(".change_pass_msg")
            .delay(5000)
            .fadeOut("slow");
    }
}

/**
 * Va la pagina de los datos del producto
 */
function goto_detail_page() {
    var name = $(".search_medicine").val();
    if (current_item_code == "" && name != "") {
        $.ajax({
            url: "medicine/add-new-medicine",
            data: "name=" + name,
            type: "POST",
            datatype: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#new_med_status").show();
                    $("#new_med_status").addClass("alert-success");
                    $("#new_med_status").html(
                        "This medicine is not available for now. Please check availability later."
                    );
                    $("#new_med_status")
                        .delay(5000)
                        .fadeOut("slow");
                } else {
                    $("#new_med_status").show();
                    $("#new_med_status").addClass("alert-danger");
                    $("#new_med_status").html(
                        "Something went wrong. Please try again later."
                    );
                    $("#new_med_status")
                        .delay(5000)
                        .fadeOut("slow");
                }
            }
        });
    } else {
        window.location = "medicine-detail/" + current_item_code;
    }
}

/**
 * Va la pagina de los datos del producto
 */
function show_detail_modal(data) {
    console.log(data);
    $.ajax({
        type: "GET",
        url: "/medicine/search-medicine/1",
        data: "term=" + encodeURIComponent(data.value),
        success: function(data) {
            if (data.result.msg[0].units_value > 0) {
                priceperunit = (data.result.msg[0].mrp / data.result.msg[0].units_value).toFixed(2);
                textPrice = "<p> Precio por Unidad </p> <p> <span style='color:green'>" + data.result.msg[0].units + "</span> a $ <span>" + priceperunit + "</span> pesos </p> ";
            } else {
                priceperunit = 0;
                textPrice = "";
            }

            //priceperunit = 0;
            console.log(data.result);
            $("#hidden_medicine_id").val(data.result.msg[0].id);
            $("#hidden_item_code").val(data.result.msg[0].item_code);
            $("#hidden_item_pres_required").val(
                data.result.msg[0].is_pres_required
            );
            $("#hidden_selling_price").val(data.result.msg[0].mrp);
            $("#pi-med-name")
                .empty()
                .append(data.result.msg[0].name);

            if (data.result.msg[0].units != 'NoD') {
                $("#pi-med-units-price")
                    .empty()
                    .append(textPrice);
                // $("#pi-med-unit-value")
                //     .empty()
                //     .append(data.result.msg[0].units);

                // $("#pi-med-unit-price")
                //     .empty()
                //     .append(priceperunit);
            } else {
                $("#pi-med-units-price")
                .empty();
            }

            $("#pi-med-description")
                .empty()
                .append(data.result.msg[0].composition);
            $("#pi-med-img")
                .empty()
                .attr('src', data.result.msg[0].url_img);
            $("#pi-med-composition").val(data.result.msg[0].composition);
            $("#pi-med-comby").val(data.result.msg[0].lab);
            $("#pi-med-form").val(
                data.result.msg[0].is_pres_required ? "RX" : "Opcional"
            );
            $("#pi-med-typm").val(data.result.msg[0].group);
            $("#pi-med-price-unit").val(data.result.msg[0].mrp);
            $("#pi-med-price").val(
                "$ " +
                    (data.result.msg[0].mrp * $("#pi-med-quantity").val())
                        .toString()
                        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
            );
            if(data.result.msg[0].quantity > 50) {
                $("#pi-med-avail").html('<span style="color:green"> Disponible </span>');
                $(".add_to_cart").prop('disabled', false);
            } else {
                $("#pi-med-avail").html('<span style="color:red"> No Disponible </span>');
                $(".add_to_cart").prop('disabled', true);
            }

            $("#pinfo-modal").modal("show");
        }
    });
}

function show_our_products(cat = null) {
    console.log("Categoria:" + cat);

    $.ajax({
        url: "medicine/search-medicine/1",
        data: "cat=" + cat + "&lab=icom",
        type: "GET",
        async: false,
        datatype: "JSON",
        success: function(data) {
            console.log("Productos:");
            console.log(data);
            $("#med-list").empty();
            // $('#med-list').append('<div class="col-lg-12 col-md-12 col-sm12"><h4> Productos Recomendados </h4></div>')
            $.each(data.result.msg, function(i, item) {
                //console.log(item);
                medicina = item.name;
                $("#med-list").append(
                    '<div class="med cat_product" onclick="thumbClick(`' + medicina + '`)">' +
                        '    <div class="row">' +
                        '        <div class="col-md-4">' +
                        '            <img class="med-thumbnail" src="' + item.url_img + '" alt="">' +
                        "        </div>" +
                        '        <div class="col-md-8">' +
                        '            <h5 class="med-title">' +
                        item.name +
                        "</h5>" +
                        '            <p class="med-description"> ' +
                        item.composition +
                        " </p>" +
                        '            <p class="med-mrp" style="text-align:right; font-size:2e; color:green">  $' +
                        convertToMoney(item.mrp) +
                        " </p>" +
                        "        </div>" +
                        "    </div>" +
                        "</div>"
                );
            });
        }
    });

    $.ajax({
        url: "medicine/search-medicine/1",
        data: "cat=" + cat + "&xlab=icom",
        type: "GET",
        datatype: "JSON",
        success: function(data) {
            console.log("Productos:");
            console.log(data);
            // $('#med-list').empty();
            // $('#med-list').append('<div class="col-lg-12 col-md-12 col-sm12"><h4> Todos los Productos </h4></div>')
            $.each(data.result.msg, function(i, item) {
                //console.log(item);
                medicina = item.name;
                $("#med-list").append(
                    '<div class="med cat_product" onclick="thumbClick(`' + medicina + '`)">' +
                        '    <div class="row">' +
                        '        <div class="col-md-4">' +
                        '            <img class="med-thumbnail" src="' + item.url_img + '" alt="">' +
                        "        </div>" +
                        '        <div class="col-md-8">' +
                        '            <h5 class="med-title">' +
                        item.name +
                        "</h5>" +
                        '            <p class="med-description"> ' +
                        item.composition +
                        " </p>" +
                        '            <p class="med-mrp" style="text-align:right; font-size:2e; color:green">  $' +
                        convertToMoney(item.mrp) +
                        " </p>" +
                        "        </div>" +
                        "    </div>" +
                        "</div>"
                );
            });
        }
    });
}

$("#catTitle").on("click", function(e) {
    // alert($(this).html());
    console.log("itemCat=" + $(this).html());
    cat_value = $(this).html();
    show_our_products(cat_value);
});

$(".catList").on("click","li", function(e) {
    alert($(this).html());
    console.log("itemCat=" + $(this).html());
    cat_value = $(this).html();
    show_our_products(cat_value);
});


document.addEventListener("click", function(event){
    //this is the event handler for the click event in myButton
    console.log ("Click : ")
    console.log (event.target);
    element = event.target;
    cat_value = element.textContent;
    console.log("itemCat=" + cat_value);
    if (element.className == "category-item") {
        show_our_products(cat_value);
    }

    //document.getElementById('myLabel').innerText=event.target.id + " was clicked";
  });

$(".category-item").on("click", function(e){
    alert($(this).html());
    console.log("itemCat=" + $(this).html());
})

$(".btn-profile").on("click", function() {
    window.location = "account-page/";
});



// Muestra el detalle de la compra seleccionada
$('.details').on('click', function(){
        $('.detail').hide();
        let detailId = "d" + $(this).data('id');
        element = document.getElementById(detailId);
        console.log($(detailId));
        console.log(element.style.display);

        show(detailId);
        // element.toggle()    //$(this).closest('div').hasClass('detail').toggle();
    })

function show(detailId) {
    if (document.getElementById(detailId).style.display = "none")
        {
            document.getElementById(detailId).style.display = "block";
            // $test="visible"
        }
    else
    {
        document.getElementById(detailId).style.display = "none";
    // $test="hidden"
    }
}


function getCategories() {
    $.ajax({
        type: "GET",
        url: "/medicine/load-medicine-cats/1",
        success: function(data) {
            // console.log(data);
            $.each(data[0].result.msg, function(i, value) {
                $("#catList").append(
                    '<li class="category-item">' + value.group + "</li>"
                );
            });
            categories = data;
        }
    });
}

$(".add_to_cart").click(function() {
    var hidden_medicine = $("#pi-med-name").html();
    var med_quantity = $("#pi-med-quantity").val();
    var hidden_item_code = $("#hidden_item_code").val();
    var hidden_selling_price = $("#hidden_selling_price").val();
    var hidden_pres_item = $("#hidden_item_pres_required").val();
    var _token = $("#_token").val();

    var id = $("#hidden_medicine_id").val();

    console.log("SellingPrice:" + $("#hidden_selling_price").val());

    if (med_quantity.length > 0 && med_quantity > 0) {
        $.ajax({
            type: "GET",
            url: "medicine/add-cart/1",
            data:
                "id=" +
                id +
                "&medicine=" +
                hidden_medicine +
                "&med_quantity=" +
                med_quantity +
                "&hidden_item_code=" +
                hidden_item_code +
                "&hidden_selling_price=" +
                hidden_selling_price +
                "&_token=" +
                _token +
                "&pres_required=" +
                hidden_pres_item,
            datatype: "json",
            complete: function(data) {},
            success: function(data) {
                console.log(data);
                if (data.msg == 0) {
                    $("#loginModal").click();
                }
                if (data.msg == "Updated") {
                    $(".med_detailes_alert").css("display", "block");
                    $(".med_detailes_alert").html(
                        "Your cart has been successfully updated."
                    );
                    $(".med_detailes_alert")
                        .delay(5000)
                        .fadeOut("slow");

                    // alert("your order is updated");
                }
                if (data.msg == "Inserted") {
                    $(".med_detailes_alert").css("display", "block");
                    $(".med_detailes_alert").html(
                        "Your cart has been successfully updated."
                    );
                    $(".med_detailes_alert")
                        .delay(5000)
                        .fadeOut("slow");
                    window.location = "my-cart/";
                }

                if (data.msg == "Sin usuario o usuario invalido") {
                    window.location = "/?msg=please_login";
                    console.log("Debe ingresar usuario y contraseña")
                }
            },
            error: function(data) {
                alert('Atencion: ' . data.msg);
            }
        });
    } else {
        $(".w_med_detailes_alert").css("display", "block");
        $(".w_med_detailes_alert").html("Please Fill quantity field");
        $(".w_med_detailes_alert")
            .delay(3000)
            .fadeOut("slow");
    }
});

function hideLoginModal() {
    $("#login-modal").modal("hide");
}

function hideRegisterModal() {
    $("#register-modal").modal("hide");
    $("#login-modal").modal("show");
}

function openProductInfoModal(product) {
    $("#pinfo-modal").modal("show");
    console.log("modal abierto");
}

function thumbClick(medItem) {
    // item = $(this).data('med');
    console.log(medItem);
    data =  {'value': medItem};
    show_detail_modal(data);
}

function convertToMoney(text) {
    return text.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

}






