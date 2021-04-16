@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/about.css" />
@endsection

@section('content')

<style type="text/css">
    /* some core files */
    b {
      font-weight: bold;
    }
</style>
<div class="about">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-8 col-sm-12" style="font-size: 85%">
                <div class="panel" id="aviso">
                    <h1 class="title" >Aviso de privacidad</h1>
                    <br>
                    Podra descargar una version en PDF de este documento <a href="/assets/pdf/aviso.pdf"> aqui </a> <br>

                    <p>
                        Con el propósito de poner a disposición del titular las políticas de tratamiento de los datos personales, se informa por este medio la existencia de las POLÍTICAS DE TRATAMIENTO DE DATOS PERSONALES- DROGUERIAS MINIFARMA S.A.S. y la forma de acceder a estas, en cumplimiento de la normativa vigente frente al tema.
                    </p>
                    <ol>
                        <li>
                            <h4> Razón Social y Datos de contacto del Responsable del Tratamiento.</h4>
                            <ol>
                                <li>Razón Social: DROGUERIAS MINIFARMA S.A.S.</li>
                                <li>NIT: 901166732-4</li>
                                <li>Dirección: Carrera 6 N° 1-20, Cajica (Cundinamarca.)</li>
                                <li>Email: info@drazamed.com</li>
                            </ol>
                        </li>
                        <br>
                        <li>
                            <h4> Finalidad por la cual se recaudan los Datos Personales.</h4>
                            <p>
                                Para ser  contactados vía mail, por los portales o telefónicamente por representantes de DROGUERIA MINIFARMA S.A.S.- Para  realizar labores relacionadas con el envío de productos a domicilio.-Para efectos de realizar la venta o el expendio de medicamentos con prescripción médica (Datos Sensibles).Para realizar encuestas de satisfacción de los contratos que se celebren con los titulares y realización de campañas de fidelización con clientes.Para la ubicación geográfica del cliente, con el propósito de expendio de productos y para la realización de estudios de mercado dentro del sector. Para entablar comunicaciones  en las que se le pongan en conocimiento de los titulares  promociones, catálogos de productos, cambios en los términos y condiciones y demás funciones relacionadas con la actividad comercial de DROGUERIAS MINIFARMA S.A.S.-Procedimientos de carácter administrativo tales como, pero sin limitarse a gestiones de carácter contable, ventas de cartera, estadísticas de ventas, gestíon de información interna, administración de Personal, etc.-Reporte de información de carácter obligatorio a Entidades públicas, por razón de sus funciones legales.- Para la transferencia necesaria de la información captada por medio  de los portales que se conserva dentro de los servidores ubicados en naciones extranjeras que proporcian niveles adecuados para la protección de los datos personales.-Envío de  correspondencia a las direcciones reportadas en el registro, de material promocional de los productos comercializados por DROGUERIAS MINIFARMA S.A.S. - Uso de datos de manera directa o por terceros ubicados en el país o en el extranjero (siempre que estos proponen nivel adecuado de protección de datos.) asignados para la realización de estudios encaminados a establecer preferencias de consumo exclusivamente en el área de productos farmacéuticos y médicos en general.

                            </p>
                        </li>
                        <li>
                            <h4> Tratamientos a los que se someten los datos Personales.</h4>
                            <p> Recolección:  Los Datos se recolectan mediante el portal web, la aplicación móvil o en los puntos de venta físicos dispuestos para la venta y distribución de medicamentos. La información se toma con la autorización directa de los titulares o eventualmente se podrá recolectar por vía de terceros, previo cumplimiento a los requisitos de ley para el tratamiento de dicha información. Forma de almacenamiento:  la información se almacena en los servidores contratados por la sociedad para estos propósitos, los cuales cumplen con las medidas de seguridad estándar conforme al estado de la técnica. Eventualmente se archivarán de manera física con medidas de seguridad pertinentes. Circulación de los Datos: La circulación es restringida y será usada por los funcionarios de la sociedad, eventualmente será compartida de conformidad a las políticas de privacidad vigentes y previamente aceptadas por el titular. Modificación del dato: El dato se modificará en cualquier momento, por la solicitud que haga el titular por conducto de los canales propuestos en las políticas de tratamiento respectivas. Eliminación del dato: La supresión se dará una vez el uso del dato termine o cuando el titular solicite la eliminación puntual de la información. </p>
                        </li>

                        <li>
                            <h4> Derechos que asisten al titular de Datos Personales. </h4>
                            <p>
                                El titular del dato personal tiene el derecho de acceder de manera gratuita en cualquier momento al dato personal; Conocer, actualizar y rectificar su información frente a datos parciales, inexactos, incompletos, fraccionados, que induzcan a error, o a aquellos cuyo tratamiento esté prohibido o no haya sido autorizado. Solicitar prueba de la autorización otorgada. Presentar ante la Superintendencia de Industria y Comercio (SIC) quejas por infracciones a lo dispuesto en la normatividad vigente. Revocar la autorización y/o solicitar la supresión del dato, siempre que no exista un deber legal o contractual que impida eliminarlos. Abstenerse de responder las preguntas sobre datos sensibles. Tendrá carácter facultativo las respuestas que versen sobre datos sensibles o sobre datos de las niñas, niños y adolescentes.
                            </p>
                        </li>

                        <li>
                            <h4>Mecanismos para acceder a los Términos y condiciones de tratamiento de los Datos Personales.</h4>
                            <p> El titular podrá acceder a la Política de Tratamiento de datos Personales, a través de la página web <a href="https://www.drazamed.com" >www.drazamed.com  </a> o dentro de la aplicación DRAZAMED  la cual se puede descargar en cualquier sistema operativo, ésta también se podrá solicitar a través del correo electrónico :  <a href="mailto:info@drazamed.com">info@drazamed.com</a> </p>
                        </li>
                    </ol>
                </div>
            </div>


            <div class="col-lg-4 col-sm-12 sm-mt-20">
                <div class="panel">
                    <h1 class="title">Documentos de Interés</h1>

                    <div class="row middle-xs">
                        <!-- <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/technology.svg"
                                alt="phone contact logo"
                            />
                        </div> -->
                        <div class="col-lg-12">
                            <h4 class="com-title"><a href="./aviso">Aviso de privacidad</a></h4>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <!-- <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/technology.svg"
                                alt="phone contact logo"
                            />
                        </div> -->
                        <div class="col-lg-12">
                            <h4 class="com-title"><a href="/terminos">Términos y Condiciones</a></h4>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <!-- <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/box.svg"
                                alt="our location contact logo"
                            />
                        </div> -->
                        <div class="col-lg-12">
                            <h4 class="com-title"><a href="/garantias">Política de Garantías</a></h4>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <!-- <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/box.svg"
                                alt="our location contact logo"
                            />
                        </div> -->
                        <div class="col-lg-12">
                            <h4 class="com-title"><a href="/devoluciones">Política de Devoluciones</a></h4>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <!-- <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/box.svg"
                                alt="our location contact logo"
                            />
                        </div> -->
                        <div class="col-lg-12">
                            <h4 class="com-title"><a href="/retracto">Política de Retracto y Reversión</a></h4>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <!-- <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/box.svg"
                                alt="our location contact logo"
                            />
                        </div> -->
                        <div class="col-lg-12">
                            <h4 class="com-title"><a href="/datos_personales">Manejo de datos Personales</a></h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
