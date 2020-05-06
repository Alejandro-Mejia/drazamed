<div
    class="modal fade"
    id="register-modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="register-modal-label"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center login-title">Crear Una Cuenta</h2>
                <form action="/user/create-user/1" method="POST">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name-input">Nombre</label>
                                <input
                                    type="text"
                                    class="form-control input-modal"
                                    id="name-input"
                                    name="first_name"
                                    placeholder="Ingrese su nombre"
                                />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="lastname-input">Apellido</label>
                                <input
                                    type="text"
                                    class="form-control input-modal"
                                    id="lastname-input"
                                    name="last_name"
                                    placeholder="Ingrese su apellido"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="phone-input">Teléfono</label>
                                <input
                                    type="tel"
                                    class="form-control input-modal"
                                    id="phone-input"
                                    name="phone"
                                    placeholder="Ingrese su teléfono"
                                />
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="account-type-input"
                                    >Tipo de Cuenta</label
                                >
                                <select
                                    class="form-control select-modal"
                                    id="account-type-input"
                                    name="user_type"
                                >
                                    <option value="5">Centro Médico</option>
                                    <option vlaue="3">Cliente</option>
                                    <option value="4">Domiciliario</option>
                                    <option value="2"
                                        >Profesional Médico</option
                                    >
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address-input">Dirección</label>
                        <input
                            type="text"
                            class="form-control input-modal"
                            id="address-input"
                            name="address"
                            placeholder="Ingrese su dirección"
                        />
                    </div>

                    <div class="form-group">
                        <label for="email-input">Correo Electrónico</label>
                        <input
                            type="email"
                            class="form-control input-modal"
                            id="email-input"
                            name="email"
                            placeholder="Ingrese su correo electrónico"
                        />
                    </div>

                    <div class="form-group">
                        <label for="pwd-input">Contraseña</label>
                        <input
                            type="password"
                            class="form-control input-modal"
                            id="pwd-input"
                            name="password"
                            placeholder="Ingrese su contraseña"
                        />
                    </div>
                    <button type="submit" class="mt-5 login-btn">
                        Registrarme
                    </button>
                </form>

                <p
                    class="text-center drazamed-link"
                    onclick="hideRegisterModal()"
                >
                    ¿Ya tienes una cuenta? Inicia sesión
                </p>
            </div>
        </div>
    </div>
</div>
