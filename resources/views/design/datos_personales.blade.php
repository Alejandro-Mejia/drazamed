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
                    <h1 class="title" > POLÍTICAS DE TRATAMIENTO DE DATOS PERSONALES- DROGUERIAS MINIFARMA S.A.S.</h1>
                    <br>
                    Podra descargar una version en PDF de este documento <a href="/assets/pdf/datos_personales.pdf"> aqui </a>


                    <h2> INDICE: </h2>
                    <ol>
                        <li>
                            Definiciones.
                        </li>
                        <li>
                            Principios rectores para el manejo de Datos Personales.
                        </li>
                        <li>
                            Identidad de la Persona Jurídica que realiza el tratamiento.
                        </li>
                        <li>
                            Propósito por el cual se realiza  la  recolección y Ciclo del Dato Personal.
                        </li>
                        <li>
                            Derechos de los titulares de Datos Personales.
                        </li>
                        <li>
                            Disposiciones Finales.
                        </li>
                    </ol>

                    <h2> 1. DEFINICIONES </h2>

                    <ol>
                        <li>
                            <b>Autorización: </b> Consentimiento previo, expreso e informado del Titular de los datos personales (usuario) para llevar a cabo el Tratamiento de datos personales;
                        </li>
                        <li>
                            <b>Base de Datos:</b> Conjunto organizado de datos personales que sea objeto de Tratamiento;
                        </li>
                        <li>
                            <b>Canales físicos: </b> Lugares o establecimientos dispuestos para la venta de los productos ofrecidos por DROGUERIAS MINIFARMA S.A.S.
                        </li>
                        <li>
                            <b>Ciclo de Vida del Dato:</b> Modo en el que  el dato personal fluye dentro de la organización empresarial, durante el proceso comercial desarrollado mediante los portales o presencialmente.
                        </li>
                        <li>
                            <b>Cliente:</b> aquella persona natutral o jurídica  que con ocasión a los contratos de compraventa de bienes muebles celebrados con DROGUERIA MINIFARMA S.A.S. hace las veces de comprador de los productos ofrecidos en LOS PORTALES y en los canales físicos Para efectos de las presentes políticas, el cliente, es el mismo titular.
                        </li>
                        <li>
                            <b>Cookies: </b>herramientas digitales que permiten almacenar en memoria y por cierto tiempo, diferentes tipos de información. Sumado a la base de datos y la capa de datos y lógica de los portales, los Cookies permiten obtener información fundamental acerca del comportamiento de los usuarios dentro de los portales como tales, pero sin limitarse a: tendencia de consumo, medicamentos más buscados, ubicación geográfica entre otros.
                        </li>
                        <li>
                            <b>Dato Personal:</b> Cualquier información vinculada o que pueda asociarse a una o varias personas naturales determinadas o determinables;
                        </li>
                        <li>
                            <b>Dato Público:</b> Es el dato que no sea semiprivado, privado o sensible. Son considerados datos públicos, entre otros, los datos relativos al estado civil de las personas, a su profesión u oficio y a su calidad de comerciante o de servidor público. Por su naturaleza, los datos públicos pueden estar contenidos, entre otros, en registros públicos, documentos públicos, gacetas y boletines oficiales y sentencias judiciales debidamente ejecutoriadas que no estén sometidas a reserva.
                        </li>
                        <li>
                            <b>Dato semiprivado:</b>  Es el dato que no tiene naturaleza íntima, reservada, ni pública y cuyo conocimiento o divulgación puede interesar no sólo a su titular sino a cierto sector o grupo de personas.
                        </li>
                        <li>
                            <b>Dato Sensible:</b> se entiende por datos sensibles aquellos que afectan la intimidad del Titular o cuyo uso indebido puede generar su discriminación, tales como aquellos que revelen el origen racial o étnico, la orientación política, las convicciones religiosas o filosóficas, la pertenencia a sindicatos, organizaciones sociales, de derechos humanos o que promueva intereses de cualquier partido político o que garanticen los derechos y garantías de partidos políticos de oposición así como los datos relativos a la salud, a la vida sexual y los datos biométricos.
                        </li>
                        <li>
                            <b>Encargado del Tratamiento:</b> Persona natural o jurídica, pública o privada, que por sí misma o en asocio con otros, realice el Tratamiento de Datos Personales por cuenta del responsable del Tratamiento;
                        </li>
                        <li>
                            <b>Los Portales:</b> Es la aplicación o sitio web que ofrece al público los productos farmacéuticos y medicinales, cosméticos y artículos de tocador en general, así como otros productos para ser vendidos por los canales antes señalados y entregados al consumidor final a domicilio dentro del área de cobertura correspondiente.
                        </li>
                        <li>
                            <b>Medidas de Protección Reforzada:</b> Medidas a ser adoptadas en los casos en que por intermedio de LOS PORTALES se reciban Datos Personales de menores de edad. No obstante, a lo anterior el Oficial de Tratamiento de Datos Personales deberá rechazar los datos de menores de edad sobre los cuales no se obtenga autorización de los acudientes de estos.
                        </li>
                        <li>
                            <b>Oficial de Tratamiento de Datos Personales:</b> Persona encargada de la administración y tratamiento de los datos personales recolectados por intermedio LOS PORTALES y los canales físicos de venta.
                        </li>
                        <li>
                            <b>Responsable del Tratamiento:</b> Persona natural o jurídica, pública o privada, que por sí misma o en asocio con otros, decida sobre la base de datos y/o el tratamiento de los datos.
                        </li>
                        <li>
                            <b>Superintendencia de Industria y Comercio (en adelante SIC):</b> Autoridad Pública del Orden Nacional encargada de vigilar, inspeccionar y controlar a las personas naturales y jurídicas que administran Datos de carácter Personal.
                        </li>
                        <li>
                            <b>Titular:</b> Persona Natural cuyos datos personales sean objeto de Tratamiento; para esta política en particular, el titular, es el mismo cliente.
                        </li>
                        <li>
                            <b>Términos y condiciones de LOS PORTALES:</b> acuerdo de voluntades celebrado con los usuarios en donde se consignan los derechos y obligaciones de las partes que suscriben el mentado acuerdo para efectos de navegar y celebrar contratos de compraventa por intermedio de las plataformas de comercio electrónico.
                        </li>
                        <li>
                            <b>Transferencia:</b> La transferencia de datos tiene lugar cuando el responsable y/o Encargado del Tratamiento de datos personales, ubicado en Colombia, envía la información o los datos personales a un receptor, que a su vez es Responsable del Tratamiento y se encuentra dentro o fuera del país.
                        </li>
                        <li>
                            <b>Transmisión:</b> Tratamiento de datos que implica la comunicación de los mismos dentro o fuera del territorio de la República de Colombia cuando tenga por objeto la realización de un Tratamiento por el Encargado por cuenta del responsable.
                        </li>
                        <li>
                            <b>Tratamiento:</b> Cualquier operación o conjunto de operaciones sobre datos personales, tales como la recolección, almacenamiento, uso, circulación o supresión.
                        </li>
                    </ol>

                    <h2> 2. PRINCIPIOS RECTORES PARA EL MANEJO DE DATOS PERSONALES. </h2>

                    <ol>
                        <li>
                            <b>Principio de legalidad en materia de Tratamiento de datos:</b> Principio de legalidad en materia de Tratamiento de datos: El Tratamiento es una actividad reglada que debe sujetarse a lo establecido en las Leyes y en las demás disposiciones que la desarrollen;
                        </li>
                        <li>
                            <b>Principio de finalidad:</b> El Tratamiento debe obedecer a una finalidad legítima de acuerdo con la Constitución y la Ley, la cual debe ser informada al Titular;
                        </li>
                        <li>
                            <b>Principio de libertad:</b> El Tratamiento sólo puede ejercerse con el consentimiento, previo, expreso e informado del Titular. Los datos personales no podrán ser obtenidos o divulgados sin previa autorización, o en ausencia de mandato legal o judicial que releve el consentimiento;
                        </li>
                        <li>
                            <b>Principio de veracidad o calidad:</b> La información sujeta a Tratamiento debe ser veraz, completa, exacta, actualizada, comprobable y comprensible. Se prohíbe el Tratamiento de datos parciales, incompletos, fraccionados o que induzcan a error;
                        </li>
                        <li>
                            <b>Principio de transparencia:</b>  En el Tratamiento debe garantizarse el derecho del Titular a obtener del responsable del Tratamiento o del Encargado del Tratamiento, en cualquier momento y sin restricciones, información acerca de la existencia de datos que le conciernan;
                        </li>
                        <li>
                            <b>Principio de acceso y circulación restringida:</b> El Tratamiento se sujeta a los límites que se derivan de la naturaleza de los datos personales, de las disposiciones de las leyes aplicables y de la Constitución. En este sentido, el Tratamiento sólo podrá hacerse por personas autorizadas por el Titular y/o por las personas previstas en la en la normativa aplicable;
                        </li>
                        <li>
                            <b>Principio de seguridad:</b> La información sujeta a Tratamiento por el Responsable del Tratamiento o Encargado del Tratamiento a que se refiere la presente ley, se deberá manejar con las medidas técnicas, humanas y administrativas que sean necesarias para otorgar seguridad a los registros evitando su adulteración, pérdida, consulta, uso o acceso no autorizado o fraudulento;
                        </li>
                        <li>
                            <b>Principio de confidencialidad:</b> Todas las personas que intervengan en el Tratamiento de datos personales que no tengan la naturaleza de públicos están obligadas a garantizar la reserva de la información, inclusive después de finalizada su relación con alguna de las labores que comprende el Tratamiento, pudiendo sólo realizar suministro o comunicación de datos personales cuando ello corresponda al desarrollo de las actividades autorizadas en la presente ley y en los términos de esta.
                        </li>

                    </ol>

                    <h2> 3. IDENTIDAD DE LA PERSONA JURÍDICA RESPONSABLE DEL TRATAMIENTO. </h2>

                    <p>
                        Con el propósito de cumplir con la normativa vigente que regula el Tratamiento de Datos Personales, se informa a los titulares la identidad de la persona jurídica que realizará la mencionada labor:

                        <ol>
                            <li>
                                Razón Social: DROGUERIAS MINIFARMA S.A.S.
                            </li>
                            <li>
                                NIT: 901166732-4
                            </li>
                            <li>
                                Dirección: Carrera 6 N° 1-20, Cajica (Cundinamarca.)
                            </li>
                            <li>
                                Email: <a href="mailto:info@drazamed.com">info@drazamed.com</a>
                            </li>
                        </ol>
                    </p>

                    <h2>4.  PROPÓSITO POR EL CUAL SE REALIZA  LA  RECOLECCIÓN Y CICLO DEL DATO PERSONAL.</h2>

                    <ol>
                        <li>
                            <b>Causa  jurídica de la utilización de los Datos Personales:</b> por intermedio de los portales y canales físicos DROGUERIA MINIFARMA S.A.S. ofrece productos farmacéuticos y medicinales, cosméticos y artículos de tocador en general, así como otros productos para ser vendidos por los canales antes señalados. Al mismo tiempo se realiza el recaudo de información con propósitos comerciales y estadísticos para mejorar la experiencia de servicio de conformidad con las preferencias de consumo del cliente y con la información que voluntariamente cada usuario consigne en la aplicación, tales como, pero sin limitarse a, preferencias en productos, cantidades y fechas en que se deben consumir los productos vendidos, etc.
                            <br>
                            En este sentido y teniendo en cuenta el objeto de la actividad comercial, el responsable informa a los titulares que estas serán las actividades para las cuales se dará el tratamiento de los datos personales:

                            <ol>
                                <li>
                                    Para ser  contactados vía mail, por los portales, y/o telefónicamente por representantes de DROGUERIA MINIFARMA S.A.S.
                                </li>
                                <li>
                                    Para  realizar labores relacionadas con el envío de productos a domicilio.
                                </li>
                                <li>
                                    Para efectos de realizar la venta o el expendio de medicamentos con prescripción médica (Datos Sensibles).
                                </li>
                                <li>
                                    Para generar recordatorios del consumo de medicamentos a cada uno de los titulares.
                                </li>
                                <li>
                                    Para realizar encuestas de satisfacción de los contratos que se celebren con ocasión de los contratos y realización de campañas de fidelización  con la clientela.
                                </li>
                                <li>
                                    Para la ubicación geográfica del cliente, con el propósito de expendio de productos y para la realización de estudios de mercado dentro del sector.
                                </li>
                                <li>
                                    Para entablar comunicaciones  en las que se le pongan en conocimiento de los titulares  promociones, catálogos de productos, cambios en los términos y condiciones y demás funciones relacionadas con la actividad comercialde DROGUERIAS MINIFARMA S.A.S.
                                </li>
                                <li>
                                    Procedimientos de carácter administrativo tales como pero sin limitarse a gestiones de carácter contable, ventas de cartera, estadísticas de ventas, gestíon de información interna, administración de Personal, etc.
                                </li>
                                <li>
                                    Para la transferencia necesaria de la información captada por conducto de los portales que se conserva dentro de los servidores ubicados en naciones extranjeras que proporcian niveles adecuados para la protección de los datos personales.
                                </li>
                                <li>
                                    Reporte de información de carácter obligatorio a Entidades Públicas, por razón de sus funciones legales.
                                </li>
                                <li>
                                    Envío de  correspondencia a las direcciones reportadas en el registro, de material promocional de los productos comercializados por DROGUERIAS MINIFARMA S.A.S.
                                </li>
                                <li>
                                    Uso de datos de manera directa o por terceros ubicados en el país o en el extranjero (siempre que estos proponen nivel adecuado de protección de datos.) asignados para la realización de estudios encaminados a establecer preferencias de consumo exclusivamente en el área de productos farmacéuticos y médicos en general.
                                </li>
                            </ol>
                        </li>
                        <li>
                            <b>Datos solicitados a los Titulares sometidos a protección: </b> Los Datos Personales son los siguientes:
                            <ol>
                                <li>Nombre completo</li>
                                <li>Edad</li>
                                <li>Cédula.</li>
                                <li>Género.</li>
                                <li>Fecha de nacimiento</li>
                                <li>Datos relacionados con los Convenios celebrados con entidades del sector salud.</li>
                                <li>Direccción de domicilio</li>
                                <li>Dirección de correo electrónico.</li>
                                <li>Teléfono Fijo o celular</li>
                                <li>Foto de perfil del usuario (opcional).</li>
                                <li>Datos relacionados con la firma física o electrónica, nacionalidad, fecha de nacimiento,</li>
                                <li>Datos relacionados con ubicación desde sistemas de información tales como IP, claves perfiles, ubicaciones georeferenciadas, etc.</li>
                                <li>Datos Biométricos personales, tales como huellas dactilares, rasgos relacionados con la geometría corporal, representaciones graficas corporales, contenidas en fotografías videos, datos morfólogicos de personas en general. (DATOS SENSIBLES)</li>
                                <li>Datos contenidos dentro de las prescripciones médicas, relacionados con la salud en general de  los titulares. (DATOS SENSIBLES)</li>
                                <li>Datos relacionados con la pertenencia a organizaciones sociales tales como sindicatos, de derechos humanos, religiosos, políticos, filosóficos, de identidad o de orientación sexual, o de origen étnico racial. (DATOS SENSIBLES)</li>
                                <li>Datos socio-económicos de personal relacionados con experiencia laboral. (DATOS SENSIBLES)</li>
                            </ol>
                        </li>
                        <li>
                            <b> Datos Sensibles.</b> <br>
                            En los casos  en que se recolecten datos sensibles, los cuales se identifican claramente en el numeral anterior, el titular no está obligado a autorizar su Tratamiento. La finalidad del uso de los datos sensibles es la siguiente:

                            <ol>
                                <li>
                                    <span style="text-decoration: underline">
                                        Datos Biométricos personales, tales como huellas dactilares, rasgos relacionados con la geometría corporal, representaciones graficas corporales, contenidas en fotografías videos, datos morfólogicos de personas en general:
                                    </span> Estos datos se utilizan para la realización de registros en cámaras de seguridad de personas que ingresen a los establecimientos físicos, como requisito eventual de reconocimiento y registro en los portales web de DROGUERIAS MINIFARMA S.A.S, así como para mecanismos de reconocimiento y acceso a lugares ubicados dentro de los establecimientos de la sociedad encargada del tratamiento.
                                </li>
                                <li>
                                    <span style="text-decoration: underline">
                                        Datos contenidos dentro de las prescripciones médicas, relacionados con la salud en general de  los titulares:
                                    </span> Estos datos se utilizarán para la venta de productos farmaceutícos que de conformidad con las normas que rigen la materia, deben ser vendidas con prescripción médica. Al mismo tiempo esta informción relacionada con las dosis y posología en general se utilizará para la generación de recordatorios para la compra de nuevos medicamentos y para la organización de la entrega de los mismos.
                                </li>
                                <li>
                                    <span style="text-decoration: underline">
                                        Datos relacionados con la pertenencia a organizaciones sociales tales como sindicatos, de derechos humanos, religiosos, políticos, filosóficos, de identidad o de orientación sexual, o de origen étnico racial. (DATOS SENSIBLES)
                                    </span> Eventualmente  se puede llegar a recibir este tipo de información cuando dichas condiciones esten relacionadas con los tratamientos consignados en las formulas médicas (ejemplo: medicinas prescritas para personas con cierta condición étnica en particular). Dentro de los procesos de selección laboral o de vinculación de funcionarios eventualmente podrá llegar a conocimiento dicha información, la cual no será utilizada para realizar actividades relacionadas con discriminación y o desmejora de oportunidades de cara a la legislación vigente.
                                </li>
                                <li>
                                    <span style="text-decoration: underline">
                                        Datos socio-económicos de personal reacionados con experiencia laboral:
                                    </span> Estos datos se utilizarán  dentro de los procesos de selección de personal, para referenciar ubicación  de domicilio y posición socioconomica así como para la validación de experiencias laborales o profesionales previas y dentro de los procesos  de carácter interno que se lleven a cabo por estos temas en particular.
                                </li>

                            </ol>
                        </li>
                        <li>
                            <b> Autorización de uso de la información:  </b> Los titulares, después de recibir toda la información acerca del uso y propósito para el que se tratarán los datos personales por medio de la política correspondiente, darán su autorización mediante un botón específico dispuesto en cualquiera de los portales, esta autorización más los datos personales se ubicarán dentro de un repositorio  de información al cual tendrá acceso el oficial de manejo de datos personales y el personal que este autorice de manera expresa mediante vía escrita. <br>
                            Cuando las operaciones se realicen en los puntos físicos, se le pondrá de presente al titular el “Aviso de privacidad” en el cual se le dejará el link o el lugar en donde podrá consultar esta política previo a realizar cualquier transacción con DROGUERÍAS MINIFARMA S.A.S, para efectos de que autorice el uso de sus datos personales.
                        </li>
                        <li>
                            <b> Forma de recolección de los datos:</b>

                            <ol>
                                <li>Recolección por intermedio de los portales (Aplicación y sitio web) dispuestos por DROGUERIAS MINIFARMA S.A.S. para realizar ventas de los productos.</li>
                                <li>Recolección automática de los datos denominados COOKIES, los cuales buscan facilitar la navegación dentro de los portales dispuestos por DROGUERIAS MINIFARMA S.A.S.</li>
                                <li>Recolección en los puntos físicos de venta y atención dispuestos por DROGUERIAS MINIFARMA S.A.S.</li>
                                <li>Con ocasión al desarrollo de relaciones laborales con los colaboradores de DROGUERIA MINIFARMA S.A.S.</li>
                                <li>Durante las transacciones desarrolladas con proveedores de la sociedad DROGUERIAS MINIFARMA S.A.S.</li>
                                <li>Recolección por intermedio de registros obtenidos por cámaras de seguridad, dispuestas para propósitos de controles operacionales y por motivos de seguridad en las instalaciones de DROGUERÍAS MINIFARMA S.A.S.</li>
                            </ol>

                        </li>
                        <li>
                            <b>Forma en la que se almacenan y custodian los Datos Personales</b>

                            <p>
                                El almacenamiento y custodia de los Datos Personales será responsabilidad del Oficial de Tratamiento de Datos Personales quien se encargará de cumplir con la normativa aplicable, con los presentes términos Condiciones y con la política interna de DROGUERIAS MINIFARMA S.A.S.
                                <br>
                                Los Datos personales captados, tanto por medios físicos como electrónicos, se mantendrán dentro de los servidores de la sociedad. DROGUERIAS MINIFARMA S.A.S. dispondrá para el almacenaje, las medidas de seguridad tanto electrónicas como administrativas para el resguardo de esta, en cumplimiento de la normativa aplicable, la política interna de la sociedad y de los estándares técnicos adecuados.
                            </p>
                        </li>
                        <li>
                            <b>
                                Consulta de Datos Personales.
                            </b> <br>
                            Para efectos de consultar los datos personales, la implementación que le está dando a los datos o la autorización de uso que se encuentren a cargo de DROGUERIAS MINIFARMA S.A.S. el titular podrá recurrir de manera gratuita a los siguientes mecanismos:

                            <ol>
                                <li>
                                    Los datos captados por intermedio de los portales se podrán consultar en los mismos en cualquier momento y sin ningún costo.
                                </li>
                                <li>
                                    El dato personal consignado por cualquier medio podrá ser consultado por intermedio de la solicitud que se haga al correo electrónico info@drazamed.com   La solicitud será atendida dentro de los diez (10) días hábiles siguientes a su recepción. En el caso de no poder atenderla dentro del término anteriormente señalado, se le informarán los motivos de el retraso presentado y se le dará una respuesta definitiva dentro de los cinco (5) días hábiles siguientes, posteriores al vencimiento del primer término.
                                </li>
                            </ol>
                        </li>
                        <li>
                            <b>Solicitud de Eliminación, actualización o modificación de Datos Personales. </b> <br>
                            Para efectos de eliminar, actualizar o modificar los Datos Personales que residan en las bases de datos de DROGUERIA MINIFARMA S.A.S., el titular deberá seguir con el siguiente procedimiento el cual no cuenta con ningún costo:
                        </li>

                        <li>
                            El titular que desee cambiar o eliminar los datos deberá ponerse en contacto mediante cualquiera de los siguientes medios: <br>

                            <ol>
                                <li>El cliente podrá contactarse al correo electrónico <a href="mailto:info@drazamed.com">info@drazamed.com.</a></li>
                                <li>
                                    El cliente podrá dejar un mensaje en los portales de PQR´s dispuestos en los portales  de DROGUERIA MINIFARMA S.A.S.
                                </li>
                            </ol>
                        </li>
                        <li>
                            El cliente en el caso en que haga su solicitud por escrito, deberá identificar el dato que desee cambiar o eliminar, de manera completa y veraz.
                            <ol>
                                <li>El Oficial  de Datos Personales deberá realizar las modificaciones o eliminaciones solicitadas, siempre que sean consecuentes con la información referida en los presentes Términos y Condiciones dentro de los diez (10) días siguientes a la recepción de la solicitud.</li>
                                <li>En caso de que el cambio no sea consecuente por cualquier motivo el Oficial de Datos Personales lo informará al correo electrónico registrado mencionando los argumentos que justifican el rechazo.</li>
                                <li>En el caso en que el cambio sea procedente, el Oficial de Datos Personales informará al cliente acerca del cambio mediante el correo electrónico reportado.</li>
                                <li>Los cambios o modificaciones de los datos se podrán realizar dentro de la plataforma respectiva.</li>
                            </ol>
                        </li>

                        <li>
                            <b>Mecanismos de Preguntas Quejas o Reclamaciones-solicitudes de Autorización. </b> <br>
                            Para efectos de reportar cualquier eventualidad o solicitud relacionada con los datos personales los titulares tendran los siguientes medios a su disposición de manera gratuita:

                            <ol>
                                <li>El cliente podrá  realizar los cambios que desee dentro de los portales.</li>
                                <li>El cliente podrá contactarse al correo electrónico <a href="mailto:info@drazamed.com">info@drazamed.com.</a></li>
                                <li>El cliente podrá dejar un mensaje en los portales de PQR´s dispuestos en los portales  de DROGUERIA MINIFARMA S.A.S.</li>
                            </ol>
                            <br>
                            Cualquier reclamo será a atendido dentro de los quince (15) días hábiles siguientes contados a partir del día siguiente de la fecha de su recibo, en el caso en que la petición no pueda ser atendida en este lapso, se informará los motivos del retraso  en la respuesta y está se tendrá que dar en un término no mayor a ocho (8) días hábilies posteriores a la términación del término inicial.
                            <br>
                            En la circunstancia en que la solicitud se encuentre incompleta, se requerirá al titular dentro de los cinco (5) dias hábiles siguientes a la realización de la misma, para que la complemente o subsane según sea el caso. Si transcurren dos (2) meses desde la fecha en que se realizó la solicitud de complementación o subsanación de la solicitud sin que se presente alguna respuesta frente a la misma, la solicitud se entenderá como desistida.
                            <br>
                            Cuando DROGUERIAS MINIFARMA S.A.S no sea competente para resolver el reclamo, dará el traslado a quien corresponda en un máximo de dos (2) días hábiles e informará de tal situación al titular.
                            <br>
                            Este procedimiento tambien  será  de utilidad, para la solicitud de la prueba de la autorización dada de manera previa para el tratamiento de los datos personales.
                        </li>

                        <li>
                            <b>Transmisión-  Transferencia circulación de Datos Personales.</b> <br>
                            En virtud de propósito médico del suministro de medicina por intermedio de los portales, que hace parte integral del tratamiento en salud del paciente, el titular entiende, acepta y autoriza la transferencia con ocasión de la ejecución del presente contrato, de la información que residirá en servidores ubicados en el extranjero. El país en el que se encuentra el servidor proporciona niveles adecuados de protección de los datos de acuerdo con la normativa aplicable, para mayor información acerca de las características del servidor se puede consultar el siguiente link: <a href="https://aws.amazon.com/es/compliance/data-privacy-faq/">https://aws.amazon.com/es/compliance/data-privacy-faq/</a>.
                            <br>
                            En los casos en que se realice la transmisión de dichos datos para los propósitos señalados en la presente política, DROGUERIA MINIFARMA S.A.S. fijará a los terceros y personal interno una serie de medidas, que propendan por la protección de los datos bajo los criterios expuestos en estos Términos y Condiciones y la ley aplicable.  En este sentido, configurará obligaciones contractuales con los terceros y medidas de seguridad bajo el estado actual de la técnica, que persigan el pleno cumplimiento de los compromisos en el manejo de la información personal que haya sido recolectada.

                        </li>
                        <li> <b>Eliminación de los Datos Personales.</b>
                            <p>
                                Los datos se eliminarán  y se dejarán de utilizar en las siguientes circunstancias:
                            </p>
                            <ol>
                                <li>Cuando el titular reporte al responsable o encargado del tratamiento que desea que se eliminen sus Datos Personales de las Bases de Datos de DROGUERIAS MINIFARMA S.A.S.</li>
                                <li>Cuando el responsable del Dato detecte que el cliente se ha registrado en las plataformas con información falsa o con imprecisiones.</li>
                                <li>Cuando DROGUERIAS MINIFARMA S.A.S. detecte por cualquier motivo que el consumidor es menor de edad.</li>
                                <li>Cuando el titular de manera exprese informe que no acepta los cambios que se hagan a los Términos y Condiciones de los portales de DROGUERIAS MINIFARMA S.A.S.</li>
                                <li>En los casos en que así lo indiquen las leyes aplicables.</li>
                                <li>Cuando lo ordene de manera expresa la autoridad Publica, Judicial o administrativa.
                                </li>
                                <li>En el caso en que DROGUERIAS MINIFARMA S.A.S. se disuelva y entre en causal de liquidación.</li>
                                <li>Cuando DROGUERIAS MINIFARMA S.A.S. lo disponga sin mediar ninguna explicación o criterio.</li>
                            </ol>
                        </li>
                        <li>
                            <b>DERECHOS DE LOS TITULARES DE DATOS PERSONALES.</b>
                            <p>
                                Los titulares de datos personales tendrán los siguientes derechos:

                                <ol>
                                    <li>Conocer, actualizar y rectificar sus datos personales frente a los responsables del Tratamiento o Encargados del Tratamiento. Este derecho se podrá ejercer, entre otros, frente a datos parciales, inexactos, incompletos, fraccionados, que induzcan a error, o aquellos cuyo Tratamiento esté expresamente prohibido o no haya sido autorizado;</li>
                                    <li>Solicitar prueba de la autorización otorgada al responsable del Tratamiento salvo cuando expresamente se exceptúe como requisito para el Tratamiento.</li>
                                    <li>Ser informado por el responsable del Tratamiento o el Encargado del Tratamiento, previa solicitud, respecto del uso que les ha dado a sus datos personales;</li>
                                    <li>Presentar ante la Superintendencia de Industria y Comercio quejas por infracciones a lo dispuesto en la presente ley y las demás normas que la modifiquen, adicionen o complementen;</li>
                                    <li>Revocar la autorización y/o solicitar la supresión del dato cuando en el Tratamiento no se respeten los principios, derechos y garantías constitucionales y legales. La revocatoria y/o supresión procederá cuando la Superintendencia de Industria y Comercio haya determinado que en el Tratamiento el responsable o Encargado han incurrido en conductas contrarias a esta ley y a la Constitución;</li>
                                    <li>Acceder en forma gratuita a sus datos personales que hayan sido objeto de Tratamiento.</li>
                                </ol>
                            </p>
                        </li>
                        <li>
                            <b>DISPOSICIONES FINALES.</b>

                            <ol>
                                <li>Los presentes Términos y  Condiciones, se fundamentan y articulan respecto a la política interna del manejo de Datos  de DROGUERIAS MINIFARMA S.A.S. para efectos de cumplirla y aplicarla durante la práctica comercial.</li>
                                <li>La normativa aplicable en el territorio colombiano sobre Habeas Data, hará parte integrante de la presente politica  y por lo tanto estos y la jurisprudencia que se desarrolle frente al tema, se deberán tomar en consideración para la ejecución de las obligaciones aquí contenidas.</li>
                                <li>DROGUERIAS MINIFARMA S.A.S. establecerá una serie de estamentos internos que de manera armónica propenderán por la ejecución de una serie de procedimientos relacionados directamente con el tratamiento acorde con la normativa aplicable.</li>
                                <li>Es obligación de cada titular consultar, aplicar y aceptar estos Términos y condiciones para cualquier efecto relacionado con la autorización y el respectivo tratamiento de su información personal.</li>
                            </ol>
                            <br>
                            El presente documento fue aprobado por el Representante Legal de DROGUERIAS MINIFARMA S.A.S. quien firma el presente documento en señal de aprobación:

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
                            <h4 class="com-title"><a href="./terminos">Términos y Condiciones</a></h4>
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
