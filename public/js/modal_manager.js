function hideLoginModal() {
    $("#login-modal").modal("hide");
}

function hideRegisterModal() {
    $("#register-modal").modal("hide");
    $("#login-modal").modal("show");
}
