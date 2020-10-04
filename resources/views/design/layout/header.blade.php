<header>
    <div class="header-content d-flex border-bottom">
        <nav class="navbar navbar-expand-lg navbar-light" id="NavSettings" style="width: 100%"> 
            <a href="/">
                <img id="dra-logo-1" src="/assets/images/logo.png" alt="" width="300px"/>
                <img id="dra-logo-2" src="/assets/images/logo2.png" alt="" width="100px"/>
            </a>
            <button class="navbar-toggler border-0" type="button"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}
            ">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="nav nav-pills ml-auto">
                    <li id="navlistitems" class="nave-item">
                        <a class="nav-link" href="/">
                            Inicio
                        </a>
                    </li>
                    <li id="navlistitems" class="nave-item">
                        <a class="nav-link" href="/about">
                            Quienes Somos
                        </a>
                    </li>
                    <li id="navlistitems" class="nave-item">
                        <a class="nav-link" href="/contact">
                            Contacto
                        </a>
                    </li>
                    {{--<li class="nave-item">
                        @if( {{$view_name}} === "design-contact")
                            si estoy acá
                        @endif
                    </li> --}}
                    @auth    
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle border border-primary border border-primary rounded-pill" 
                                href="#"
                                id="navbarDropdownMenuLink" 
                                data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false"
                                style="background-color: #3490dc; color: #ffff">   
                                <?php
                                    if(Auth::user()->user_type_id==UserType::CUSTOMER())
                                    {
                                        $name=Auth::user()->customer;
                                        $full_name=$name->first_name." ".$name->last_name;
                                        $phone=$name->phone;
                                    }
                                    elseif(Auth::user()->user_type_id==UserType::MEDICAL_PROFESSIONAL())
                                    {
                                        $name=DB::table('ed_professional')->where('prof_mail', Auth::user()->email)->select('prof_first_name','prof_last_name','prof_phone')->first() ;
                                        $full_name=$name->prof_first_name." ".$name->prof_last_name;
                                        $phone=$name->prof_phone;
                                    }
                                ?>
                                {{ $full_name ?? '' }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink d-flex justify-content-between">
                                <a class="dropdown-item" href="/account-page">
                                    <span class="mr-10 fa fa-id-card"></span>
                                    Mi Perfil
                                </a>
                                <a class="dropdown-item" href="#por_pagar"
                                        ><span class="mr-10 fas fa-credit-card"></span
                                        >Ordenes Pendientes por Pagar
                                </a>
                                <a class="dropdown-item" href="#pagadas_por_enviar"
                                    ><span class="mr-10 fas fa-shipping-fast"></span
                                    >Ordenes en Proceso de Entrega
                                </a>
                                <a class="dropdown-item" href="#enviadas"
                                    ><span class="mr-10 fa fa-check"></span
                                    >Ordenes Finalizadas
                                </a>
                                <a class="dropdown-item" href="/my-cart"
                                    ><span
                                        class="mr-10 fa fa-shopping-cart"
                                    ></span
                                    >Carrito de Compras
                                </a>
                                <a class="dropdown-item" href="/logout"
                                        ><span
                                            class="mr-10 fas fa-sign-out-alt"
                                        ></span
                                        >Cerrar sesión
                                </a>                                
                            </div>
                        </li>
                    @else
                        <li id="navlistitems" class="nav-item">
                            <a 
                                class="nav-link border border-primary border border-primary rounded-pill"
                                href="#"
                                data-toggle="modal"
                                data-target="#login-modal"
                                style="background-color: #3490dc; color: #ffff">
                                Ingresar
                                
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
            
        </nav>
    </div>
</header>
