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
                <div class="alert alert-success success_msg" style="display: none;"></div>
                <div class="alert alert-danger failure_msg" style="display: none;"></div>
                <h2 class="text-center login-title">Crear Una Cuenta</h2>
                <form role="form" id="user_reg">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name-input">Nombre</label>
                                <input
                                    type="text"
                                    class="form-control input-modal"
                                    id="first_name"
                                    name="first_name"
                                    placeholder="Ingrese su nombre"
                                />
                            </div>
                            <p style="display: none;" id="first_name_error"></p>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="lastname-input">Apellido</label>
                                <input
                                    type="text"
                                    class="form-control input-modal"
                                    id="last_name"
                                    name="last_name"
                                    placeholder="Ingrese su apellido"
                                />
                            </div>
                            <p style="display: none;" id="last_name_error"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="phone-input">Teléfono</label>
                                <input
                                    type="tel"
                                    class="form-control input-modal"
                                    id="phone_input"
                                    name="phone"
                                    placeholder="Ingrese su teléfono"
                                />
                            </div>
                            <p style="display: none;" id="phone_error"></p>
                        </div>


                        <div class="col">
                            <div class="form-group">
                                <label for="account-type-input"
                                    >Tipo de Cuenta</label
                                >
                                <select class="form-control" id="sel1" name="user_type">
                                 <option value="0">{{_('Select')}}</option>
                                 <?php
                                    $user_type = UserType::users();

                                    foreach($user_type as $key => $type){
                                        if($type != UserType::ADMIN()){
                                            if($type != UserType::CUSTOMER()){
                                                echo "<option value='$type'>" . __($key) . "</option>";
                                            } else {
                                                echo "<option value='$type' selected>" . __($key) . "</option>";
                                            }
                                        }
                                    }
                                 ?>

                               </select>
                            </div>
                            <p style="display: none;" id="user_type_error"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address-input">Dirección</label>
                        <input
                            type="text"
                            class="form-control input-modal"
                            id="address_input"
                            name="address"
                            placeholder="Ingrese su dirección"
                        />
                    </div>
                    <p style="display: none;" id="user_address_error"></p>

                    <div class="form-group">
                        <label for="email-input-reg">Correo Electrónico</label>
                        <input
                            type="email"
                            class="form-control input-modal"
                            id="email_input_reg"
                            name="email"
                            placeholder="Ingrese su correo electrónico"
                            onblur= "CheckUsername(this.value)"
                        />
                    </div>
                    <p style="display: none;" id="user_mail_error"></p>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="pwd-input-reg">Contraseña</label>
                                <input
                                    type="password"
                                    class="form-control input-modal"
                                    id="pwd_input_reg"
                                    name="password"
                                    placeholder="Ingrese su contraseña"
                                />
                            </div>
                            <p style="display: none;" id="user_pass_error"></p>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="pwd-input-reg">Confirmar contraseña</label>
                                <input
                                    type="password"
                                    class="form-control input-modal"
                                    id="pwdconf_input_reg"
                                    name="confirm_password"
                                    placeholder="Confirme su contraseña"
                                />
                            </div>
                            <p style="display: none;" id="user_passcnf_error"></p>

                        </div>
                    </div>

                    <div class="login-fields">
                                <input type="checkbox" class="checkbox accept_terms" id="agree" checked="false" /> <label>{{ __('I am above 18 years of age and I accept all terms and conditions')}}</label>
                            </div>
                    <button type="button" class="mt-5 login-btn" id="register"  data-color="#82DCDF" onclick="user_register()">
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
