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

(function() {
    //const meds = document.getElementsByClassName("med");
    $(".med").click(el => {
        openProductInfoModal(el);
    });
})();
