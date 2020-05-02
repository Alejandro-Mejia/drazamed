<div
    class="modal fade"
    id="login-modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="login-modal-label"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" role="document" id="myModal" tabindex="-1" >
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center login-title">Iniciar Sesión</h2>
                <form id='login_form'>
                    @csrf
                    <div class="form-group">
                        <label for="email-input">Correo Electrónico</label>
                        <input
                            type="email"
                            class="form-control input-modal login_mail"
                            id="email-input"
                            name="email"
                            placeholder="Ingrese su correo electrónico"
                        />
                    </div>
                    <div class="form-group">
                        <label for="pwd-input">Contraseña</label>
                        <input
                            type="password"
                            class="form-control input-modal login_pass"
                            id="pwd-input"
                            name="password"
                            placeholder="Ingrese su contraseña"
                        />
                    </div>
                    <button type="button" class="mt-5 login-btn" onclick='login()'>
                        Iniciar Sesión
                    </button>
                </form>

                <div class="row mt-2">
                    <div class="col text-center">
                        <a
                            class="drazamed-link"
                            data-toggle="modal"
                            data-target="#recovery-modal"
                            onclick="hideLoginModal()"
                            >¿Olvidaste tu contraseña?</a
                        >
                    </div>
                    <div class="col text-center">
                        <a
                            class="drazamed-link"
                            data-toggle="modal"
                            data-target="#register-modal"
                            onclick="hideLoginModal()"
                            >¿No tienes una cuenta? Registrate</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
