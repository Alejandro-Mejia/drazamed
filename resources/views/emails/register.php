<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;  charset=utf-8"/>
    <title>Activa tu cuenta</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
</head>
<body style=" margin:0; ">
<div style="width:900px;  background:#f6f6f6;  margin:0 auto;  padding-bottom: 75px; " id="wrapper">
    <div style="width:570px;  margin:0 auto; text-align: center ">
        <div style="padding-top: 60px;  padding-bottom: 15px; " class="header-mail">
            <ul style="margin: 0; list-style: none; ">
                <li style="list-style:none;  float:center;">
                    <a href="<?php echo URL::to('/'); ?>"><img
                            src="https://drazamed.com/assets/images/logo2.png"
                            alt=" <?php echo Setting::param('site', 'app_name')['value']; ?>"
                            width="70%"
                            ></a>
                </li>
                <li style="list-style:none;  float:left">
                    <h2>
                        Contigo en tus tratamientos
                    </h2>
                </li>

            </ul>
            <div style="clear:both"></div>
        </div>
        <!--header-mail ends here-->
        <!-- <div class="banner-mail"
             style="width:570px; height:235px; overflow:hidden;background:url('https://drazamed.com/assets/images/logo.png') 55%;">
            <div style="width:100%; min-height:235px; background:rgba(61,158,179,0.8);margin-top: 0px;">
                <h2 style="font-family: 'Open Sans', sans-serif; color:#fff;font-weight: 500;margin: 0; font-size:28px;text-align:center;line-height: 3; padding-top: 40px;text-transform: capitalize">
                    <?php //echo Setting::param('site', 'app_name')['value']; ?></h2>

                <h2 style="font-family: 'Open Sans', sans-serif; color:#fff;font-weight: 100;margin: 0;font-size: 18px;text-align: center;">
                    Compra medicamentos en linea, es muy facil</h2>
            </div>
        </div> -->
        <!--banner-mail ends here-->
        <div style="background:#fff; padding-left: 30px; padding-right: 30px; padding-top: 50px; padding-bottom: 75px; "
             class="mail-content">
            <h2 style="font-family: 'Open Sans', sans-serif;  color:#272727; font-weight: 100; margin: 0;  font-size:14px; margin-bottom: 28px; ">
                Hola <span style="color:#404040; font-weight: bold; "><?php echo $name; ?></span></h2>

            <p style="font-family: 'Open Sans', sans-serif;  color:#272727; font-weight: 100; margin: 0;  font-size:14px; line-height: 1.6; margin-bottom: 25px; ">
                Gracias por registrarte a <?php echo Setting::param('site', 'app_name')['value'];?>. Queremos acompañarte en tus tratamientos siempre.</p>

            <p> Abajo encontrarás tus datos de ingreso.</p>

            <p> Usuario(email): <?php echo $user_name; ?></p>

            <p> Contraseña: <?php echo $pwd; ?></p>

            <p style="font-family: 'Open Sans', sans-serif;  color:#272727; font-weight: 100; margin: 0;  font-size:14px; line-height: 1.6; margin-bottom: 25px; ">
                Recuerda que para ingresar debes activar tu cuenta </p>

            <p style="font-family: 'Open Sans', sans-serif;  color:#272727; font-weight: 100; margin: 0;  font-size:14px; line-height: 1.6; margin-bottom: 25px; ">
                <a href="<?php echo URL::to('/'); ?>/user/web-activate-account/<?php echo $code; ?>">  haciendo click en el siguiente enlace </a></p>


            <p style="font-family: 'Open Sans', sans-serif;  color:#272727; font-weight: 100; margin: 0;  font-size:14px; line-height: 1.6; margin-bottom:0px; ">
                ¿Tienes preguntas? Escríbenos a <?php echo Setting::param('site', 'mail')['value']; ?>.</p>

            <!-- <p style="font-family: 'Open Sans', sans-serif;  color:#272727; font-weight: 100; margin: 0;  font-size:14px; line-height: 1.6; margin-bottom:0px; ">
                ¡Nuevamente gracias por registrarte!</p> -->
            <br>
            <br>

            <p style="font-family: 'Open Sans', sans-serif;  color:#d1d1d1; font-weight: 100; margin: 0;  font-size:14px; line-height: 1.6; margin-bottom:0px; border-bottom: 1px solid #f0f0f0; padding-bottom: 40px; ">
                Equipo  <?php echo Setting::param('site', 'app_name')['value']; ?></p>

        </div>
        <!--mail-content-->
        <p style=" float:left; color:#8b8b8b; font-family: 'Open Sans', sans-serif; font-weight: 100; margin: 0px; font-size:11px; line-height: 1.6; margin-top: 20px; ">
            Todos los derechos reservados.&copy; 2020 <a style="text-decoration:none"
                                                    href="<?php echo URL::to('/'); ?>"><?php echo Setting::param('site', 'website')['value']; ?></a>
        </p>

        <p style=" float:right; color:#8b8b8b; font-family: 'Open Sans', sans-serif; font-weight: 100; margin: 0px; font-size:11px; line-height: 1.6; margin-top: 20px; ">
            Unsubscribe</p>
    </div>
    <!--container ends here-->
</div>
<!--wrapper ends here-->
</body>
</html>
