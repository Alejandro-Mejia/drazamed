<header>
    <div class="header-content">
        <a href="/">
            <img src="/assets/images/logo.png" alt="" width="300px"/>
        </a>
        <nav>
            <a href="/">Inicio</a>
            <a href="/about">Quienes somos</a>
            <a href="/contact">Contacto</a>
            @if (Auth::check() )
                    <button
                        class="btn btn-login btn-profile"
                    >
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
                    </button>
                @else
                    <button
                        class="btn btn-login"
                        data-toggle="modal"
                        data-target="#login-modal"
                    >
                        Ingresar
                    </button>
                @endif


        </nav>

        <button id="side-menu-btn" type="button" class="btn-header-resp">
            <span class="fas fa-bars"></span>
        </button>

        <div id="side-menu" class="side-nav">
            <a href="#" id="side-close-btn" class="close-btn">&times;</a>

            <ul>

                <li><a href="/about">Quienes somos</a></li>
                <li><a href="/contact">Contacto</a></li>
                @if (!Auth::check() )
                    <button class="u-profile-btn">Perfil</button>
                @else
                    <button class="m-login-btn" data-toggle="modal" data-target="#login-modal">Ingresar</button>
                @endif
            </ul>
        </div>
    </div>
</header>
