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
            <div class="col-lg-8 col-sm-12">
                <div class="panel">
                    <h1 class="title" id="aviso">Aviso de privacidad</h1>
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


                <div class="panel">
                    <h1 class="title" id="aviso">Términos y condiciones portales DRAZAMED.</h1>
                    <p>
                        Este acuerdo de voluntades describe los términos y condiciones generales (en adelante los "Términos y Condiciones ") aplicables a la venta de productos farmacéuticos, cosméticos, medicinales, artículos de tocador, entre otros ofrecidos por DROGUERIAS MINIFARMA S.A.S. por intermedio del sitio web o de la aplicación (en adelante "<b>DRAZAMED APP</b>" o los portales). Las personas naturales o jurídicas que pretendan acceder a y/o adquirir los productos mediante los portales, podrán hacerlo en la medida en que acepten de manera expresa y consciente los Términos y Condiciones, junto con las políticas vigentes y futuras que se establezcan durante la vigencia del presente acuerdo.
                    </p>
                    <p>
                        <b>
                            SI POR CUALQUIER MOTIVO LA PERSONA NO ACEPTA DE MANERA EXPRESA LOS PRESENTES TÉRMINOS Y CONDICIONES GENERALES, DEBERÁ ABSTENERSE DE USARLOS.
                        </b>
                    </p>

                    <p>
                        El consumidor está en la obligación de leer, entender, aceptar y cumplir con todas las previsiones normativas dispuestas dentro de los presentes Términos y Condiciones y los demás documentos que lo integren o lo lleguen a integrar, previo al uso de los portales puestos a disposición por parte de DROGUERIAS MINIFARMA S.A.S.
                    </p>

                    <h3> Definiciones. </h3>
                    <p>
                        <b>Consumidor:</b> Toda persona natural o jurídica que, como destinatario final, adquiera, disfrute o utilice un determinado producto, cualquiera que sea su naturaleza para la satisfacción de una necesidad propia, privada, familiar o doméstica y empresarial cuando no esté ligada intrínsecamente a su actividad económica. Se entenderá incluido en el concepto de consumidor el de usuario.
                    </p>
                    <p>
                        <b>Contrato de adhesión:</b> Aquel en el que las cláusulas son dispuestas por el productor o proveedor, de manera que el consumidor no puede modificarlas, ni puede hacer otra cosa que aceptarlas o rechazarlas.
                    </p>
                    <p>
                        <b>Información: </b> Todo contenido y forma de dar a conocer la naturaleza, el origen, el modo de fabricación, los componentes, los usos, el volumen, peso o medida, los precios, la forma de empleo, las propiedades, la calidad, la idoneidad o la cantidad, y toda otra característica o referencia relevante respecto de los productos que se ofrezcan o pongan en circulación, así como los riesgos que puedan derivarse de su consumo o utilización.
                    </p>
                    <p>
                        <b>Portales: </b> Página web, aplicación nativa (app) o Web mediante los cuales DROGUERIAS MINIFARMA S.A.S ofrece a los consumidores para la venta los productos.
                    </p>
                    <p>
                        <b>Producto: </b> Todo bien que sea ofrecido mediante las plataformas dispuestas por DROGUERIAS MINIFARMA S.A.S.
                    </p>
                    <p>
                        <b>Productor: </b> Quien de manera habitual, directa o indirectamente, diseñe, produzca, fabrique, ensamble o importe productos. También se reputa productor, quien diseñe, produzca, fabrique, ensamble, o importe productos sujetos a reglamento técnico o medida sanitaria o fitosanitaria, quienes para el caso en concreto vienen a ser todos quienes produzcan los productos expedidos por DROGUERIAS MINIFARMA S.A.S. mediante los portales.
                    </p>
                    <p>
                        <b>Proveedor o expendedor:</b> Quien de manera habitual, directa o indirectamente, ofrezca, suministre, distribuya o comercialice productos con o sin ánimo de lucro, quien para este caso es DROGUERIAS MINIFARMA S.A.S.  sociedad encargada de la comercialización de los productos descritos dentro de la presente política.
                    </p>
                    <p>
                        <b>Ventas con utilización de métodos no tradicionales:</b> Son aquellas que se celebran sin que el consumidor las haya buscado, tales como las que se hacen en el lugar de residencia del consumidor o por fuera del establecimiento de comercio. Se entenderá por tales, entre otras, las ofertas realizadas y aceptadas personalmente en el lugar de residencia del consumidor, en las que el consumidor es abordado por quien le ofrece los productos de forma intempestiva por fuera del establecimiento de comercio o es llevado a escenarios dispuestos especialmente para aminorar su capacidad de discernimiento.
                    </p>
                    <p>
                        <b style="text">Ventas a distancia:</b> Son las realizadas sin que el consumidor tenga contacto directo con el producto que adquiere, que se dan por medios, tales como correo, teléfono, catálogo o vía comercio electrónico o cualquier medio digital.
                    </p>
                    <h2> Productos ofrecidos mediante los portales DRAZAMED </h2>

                    <p>
                        DROGUERIAS MINIFARMA S.A.S. por intermedio de los portales de su propiedad, ofrecerá al público los productos farmacéuticos, medicinales, cosméticos y artículos de tocador en general, así como otros productos para ser vendidos por los canales antes señalados y entregados al consumidor final a domicilio dentro del área de cobertura y términos de entrega que se describirán en los presentes términos.
                    </p>
                    <p>
                        Si bien el uso de los portales no tiene ningún costo de carácter monetario, DROGUERIAS MINIFARMA S.A.S. espera recibir de parte de sus consumidores una información de carácter personal, para efectos de mejorar la experiencia de uso de la aplicación y para que esta sea compartida con terceros interesados en ofrecer productos y servicios. Esta información será tratada de conformidad a los lineamientos contenidos dentro del <a href="#aviso"> AVISO DE PRIVACIDAD Y POLÍTICAS DE TRATAMIENTO DE DATOS PERSONALES- DROGUERIAS MINIFARMA S.A.S. </a>los cuales hacen parte del presente contrato.
                    </p>

                    <h2> Capacidad de las partes para celebrar el acuerdo </h2>

                    <p>
                        Los Servicios que se prestan mediante los portales, sólo están disponibles para personas mayores de edad, de acuerdo con lo estipulado en las leyes aplicables en la República de Colombia.
                    </p>
                    <p>
                        En el caso en que DROGUERIAS MINIFRAMA S.A.S. detecte de cualquier manera y por cualquier medio que un menor de dieciocho (18) años o personas incapaces absolutas están haciendo uso de los portales, dentro de las cuarenta y ocho (48) horas siguientes a este suceso, se negará a seguir celebrando contratos de compraventa y se encargará de clausurar la cuenta del consumidor. Además, procederá a eliminar toda la información personal que se relacione directamente con este último, de lo cual se dejará la respectiva evidencia dentro de los sistemas de información de la sociedad.
                    </p>
                    <p>
                        DROGUERIAS MINIFRAMA S.A.S se abstendrá de entregar medicamentos a personas que una vez hayan sido identificadas sean menores de edad o incapaces absolutos. Lo anterior no podrá ser entendido como un incumplimiento del contrato de compraventa y como tal esta será justa causa para dar por terminado el vínculo negocial.

                    </p>

                    <h2> Inscripción como consumidor dentro de los Portales. </h2>

                    <p>
                        Para efectos de hacer uso de los portales dispuestos por DROGUERIAS MINIFARMA S.A.S., el consumidor deberá registrase como consumidor dentro de los mismos. Los datos que le serán exigidos a cada consumidor son los siguientes:
                    </p>
                    <p>
                        <ol type="a">
                            <li>Nombre y apellidos.</li>
                            <li>Teléfono fijo o celular.</li>
                            <li>Dirección de correo electrónico.</li>
                            <li>Dirección en la que se realizarán los envíos.</li>
                            <li>Cédula de Ciudadanía.</li>
                            <li>Datos relacionados con los Convenios celebrados con entidades del sector salud.</li>
                            <li>Fecha de Nacimiento.</li>
                            <li>Genero.</li>
                        </ol>
                    </p>
                    <p>
                        El consumidor se compromete a mantener información fidedigna y actualizada de sus datos personales consignados dentro de los portales, lo cual permitirá a DROGUERIAS MINIFARMA S.A S. prestar el servicio de manera efectiva y segura.
                    </p>
                    <p>
                        DROGUERIAS MINIFARMA S.A S. a su sola discreción y cuando lo considere necesario, podrá ponerse en contacto con el consumidor para efectos de corroborar sus datos personales u otros que hayan consignado por cualquier vía dentro de la aplicación. En la circunstancia en que no se pueda surtir la confirmación de dicha información anteriormente relacionada DROGUERIAS MINIFARMA S.A.S procederá a cerrar la cuenta del consumidor y a eliminar todos sus Datos Personales.
                    </p>
                    <p>
                        El consumidor se hace responsable frente a DROGUERIAS MINIFARMA S.A.S y terceros por cualquier uso indebido de su cuenta; sea que se realice con o sin su consentimiento previo e indemnizará a DROGUERIAS MINIFARMA S.A.S por los daños que se generen.
                    </p>
                    <p>
                        La cuenta de cada consumidor es personal, única e intransferible. Está prohibido que un mismo consumidor inscriba o posea más de una cuenta dentro del sistema de la aplicación, así como suplantar a una persona o crear y utilizar una cuenta para una persona inexistente. En caso de que se detecte esta situación DROGUERIAS MINIFARMA S.A S. tendrá la potestad de clausurar y eliminar del sistema estas cuentas de manera unilateral sin ningún tipo de preaviso.
                    </p>
                    <p>
                        DROGUERIAS MINIFARMA S.A S. sin perjuicio de adelantar otros mecanismos de carácter legal, se reserva el derecho de eliminar cualquier cuenta sin previa notificación o aviso y sin necesidad de contar con una causa o razón en específico para ello, situación que es entendida y aceptada desde este momento.

                    </p>

                    <h2> Celebración y perfeccionamiento de los contratos de compraventa. </h2>

                    <p>
                        DROGUERIAS MINIFARMA S.A.S por intermedio de sus portales, presentará ofertas dirigidas a personas no determinadas por intermedio de propaganda escrita y visual de conformidad a la normativa aplicable.
                    </p>
                    <p>
                        El contrato de compraventa de los productos ofrecidos mediante los portales de DROGUERIAS MINIFARMA S.A.S se perfecciona una vez el consumidor acepte el producto que recibirá y el precio que deberá pagar por el mismo, lo cual se documentará en los portales una vez acepte la oferta realizada por DROGUERIAS MINIFARMA S.A S.
                    </p>
                    <p>
                        Previo a realizar la transacción de compra, el portal le presentará la información que a continuación se enumera:
                    </p>

                    <p>
                        <ol>
                            <li>
                                Información de teléfonos de contacto, correo electrónico y demás canales de contacto de DROGUERIAS MINIFARMA S.A.S.
                            </li>
                            <li>
                                Información relacionada con la cobertura para la prestación del servicio domiciliario.
                            </li>
                            <li>
                                Información relacionada con la denominación comercial, el gramaje, la cantidad de grajeas, píldoras, pastillas y demás información que sirva para la identificación del producto.
                            </li>
                            <li>
                                Existencias del producto presentado dentro del portal.
                            </li>
                            <li>
                                Información acerca de la escala a la que se encuentra la imagen dentro de los portales.
                            </li>
                            <li>
                                Identificación de los métodos de pago dispuestos para la transacción, así como el procedimiento para ejecutar el Derecho de Retracto.
                            </li>
                            <li>
                                Información correspondiente a los impuestos aplicables a la compra.
                            </li>
                            <li>
                                Previo a la celebración efectiva del contrato de compraventa DROGUERIAS MINIFARMA S.A.S presentará al consumidor el resumen de todos los productos adquiridos en la compra, con el respectivo precio individual de cada uno de ellos, los impuestos correspondientes si aplican, el precio total de la transacción y por separado el costo correspondiente al envío. La información aquí descrita podrá ser impresa o descargada por el consumidor cuando así lo disponga.
                            </li>

                            <p>
                                El consumidor tendrá el derecho y la posibilidad a través de la aplicación, de cancelar en cualquier momento, previo a que confirme la compra.
                            </p>
                            <p>
                                En el caso en que el medicamento que se vaya a adquirir necesite una receta o fórmula médica, los portales se lo notificarán de manera inmediata para que el consumidor, por intermedio del mismo portal, adjunte una foto de dicha receta o fórmula médica.  Para efectos de confirmar la veracidad de la receta, el consumidor deberá entregar al domiciliario que haga entrega del producto en el lugar indicado, la fórmula para que haga el cotejo respectivo con la que se relacionó e incluyó en el portal al momento de hacer la solicitud del producto.
                            </p>
                            <p>
                                Una vez realizado el clic en el botón “Comprar” DROGUERIAS MINIFARMA S.A S. enviará hasta el día siguiente de haberse realizado la transacción, un reporte en el que se confirmará la recepción del pedido realizado y a su vez se informará el rango de tiempo en el que se estima llegará el mismo. Sumado a lo anterior, se confirmará el precio, los gastos de mensajería y la forma escogida por el cliente para el pago.
                            </p>
                            <p>
                                En los casos de no encontrarse en existencias el producto ofrecido mediante los portales, DROGUERIAS MINIFARMA S.A.S. informará inmediatamente al consumidor. DROGUERIAS MINIFARMA S.A.S. no se hará responsable por ningún motivo, en los casos en que el consumidor no encuentre un producto en particular en las existencias de los portales.
                            </p>

                        </ol>
                    </p>

                    <h2> Recordatorio para la venta de medicamentos. </h2>

                    <p>
                        DROGUERIAS MINIFARMA S.A.S en cualquiera de las plataformas dispondrá de un sistema de recordatorios a los consumidores, en los casos en que desee comprar de manera recurrente alguno o varios de los productos ofrecidos mediante el portal.
                        <br>
                        El sistema de recordatorios procederá de la siguiente manera:
                    </p>

                    <p>
                        <ol type="a">
                            <li>
                                a)  Cuando el usuario se disponga a finalizar la compra de su producto, el sistema del portal le enviará un mensaje, en el cual mencionará lo siguiente:
                                <br>
                                “Mientras esperas puedes programar alertas para recordar cuándo tomar tus medicinas.”
                            </li>
                            <li>
                                Una vez el consumidor programe el recordatorio, con doce (12) horas de antelación a la(s) fecha(s) que se indique(n), llegará un mensaje al correo electrónico y al celular registrados al inicio, en donde se compartirá un Link mediante el cual el consumidor podrá revisar toda la información prevista en esta política sobre la compra en particular.
                            </li>
                            <li>
                                Una vez realizado el clic en el botón de compra respectivo DROGUERIAS MINIFARMA S.A.S. enviará hasta el día siguiente de haberse realizado la transacción, un reporte en el que se confirmará la recepción de la solicitud y a su vez se informará el rango de tiempo en el que se estima llegará el pedido. Sumado a lo anterior, se confirmará el precio, los gastos de mensajería y la forma en que se deberá realizar el pago.
                            </li>
                        </ol>
                    </p>

                    <p>
                        El consumidor entiende y asume que las transacciones de venta gracias a los recordatorios no implican la celebración de contratos de suministro, sociedad, Joint Venture, cuentas en participación o cualquier otra figura diferente a la inicialmente enunciada.
                        <br>
                        DROGUERIAS MINIFARMA S.A.S. informa que los recordatorios son un mecanismo coordinado y administrado por los mismos consumidores, para facilitar sus compras futuras mediante la aplicación y por lo tanto no se hace responsable personal o patrimonialmente de cualquier consecuencia adversa que pueda sufrir el consumidor, relacionada con fallas en la generación de recordatorios o por la inobservancia de los mismos, que generen alteraciones en los tratamientos médicos o daños en la salud de cualquier consumidor generados por los errores  del sistema de alarmas propuesto dentro de las plataformas .
                    </p>

                    <h2> Información y publicidad de los productos ofrecidos. </h2>

                    <p>
                        DROGUERIAS MINIFARMA S.A.S. no produce ninguno de los productos ofrecidos mediante los portales, por lo tanto, no genera información relacionada con los bienes que se ofrecen en los portales. En virtud de lo anterior DROGUERIAS MINIFARMA S.A.S. no será responsable por la información de cada producto relacionada con sus propiedades, contraindicaciones, efectos colaterales, posología, etc. la cual sólo se encontrará en el empaque de cada uno de los artículos que se adquieran.
                        <br>
                        Es responsabilidad de cada consumidor previo a adquirir cada producto, informarse con el fabricante (sitios web de) y/o con su médico tratante, acerca de estas propiedades y demás contraindicaciones del caso.
                        <br>
                        La información de precios sugeridos no es vinculante para DROGUERIAS MINIFARMA S.A.S toda vez que al ser sugeridos la sociedad puede fijarlos libremente, salvo que el producto cuente con un control de precios previamente establecido. No obstante, DROGUERIAS MINIFARMA S.A.S tiene el deber de informar dichos precios de conformidad con la legislación que rige la materia.  Esta fijación se dará con excepción de los medicamentos o productos que por ley tenga un límite en su precio el cual será debidamente observado y aplicado en cada compraventa.
                        <br>
                        En el caso en que por intermedio de los portales se adelanten campañas publicitarias DROGUERIAS MINIFARMA S.A.S no será responsable por aquellas que sean administradas y realizadas por los propietarios de los productos ofrecidos en las plataformas. DROGUERIAS MINIFARMA S.A.S. al ser el medio por el cual se publican dichas campañas, sólo será responsable cuando se le compruebe culpa grave o dolo de conformidad a la normativa aplicable.
                    </p>
                    <h2> Retracto y Reversión del pago. </h2>
                    <p>
                        En los casos en que los consumidores deseen realizar el retracto o la reversión del pago realizado por medios electrónicos, el consumidor deberá seguir los parámetros establecidos de la política denominada <a href="#retracto">POLÍTICA DE DEVOLUCIÓN Y RETRACTO DRAZAMED</a>, la cual hace parte integrante del presente contrato.
                    </p>

                    <h2> Modificaciones de los Términos y condiciones. </h2>

                    <p>
                        DROGUERIAS MINIFARMA S.A S. estará en la posibilidad de modificar los presentes Términos y Condiciones Generales en cualquier momento siempre que realice la respectiva notificación de ello dentro de la aplicación, vía mail o cualquier otro medio de los términos modificados. Todos los términos modificados entrarán en vigor a los (diez) 10 días posteriores a su publicación o comunicación respectiva. El consumidor tendrá la posibilidad de comunicar dentro de los 5 (cinco) días hábiles siguientes a la publicación de las modificaciones. Vencido este plazo, y si el consumidor hace uso de la aplicación para cualquier motivo, bajo la vigencia de los nuevos Términos y Condiciones, será muestra inequívoca de que está de acuerdo con las modificaciones contractuales y como tal las acepta y estas entrarán a regir a partir del momento en que el consumidor haga uso de cualquiera de los portales en cualquier actuación o transacción.
                    </p>

                    <h2>Manejo del contenido consignado en los portales DRAZAMED.</h2>

                    <p>
                        El consumidor es responsable por toda la información personal, sensible, imágenes, videos, datos, vínculos de internet y demás información que consigne dentro de los portales. El consumidor tiene la obligación de abstenerse de publicar información que sea ofensiva, incompleta, falsa, difamatoria, racialmente ofensiva, que constituya abuso o acoso que viole derechos de terceros (derechos patrimoniales, derechos de propiedad Intelectual, etc.)
                        <br>
                        Al aceptar los presentes términos y condiciones, los consumidores entregan una licencia de término indefinido y a nivel mundial a DROGUERIASMINIFARMA S.A S. sin ningún costo, para utilizar, reproducir, distribuir, crear trabajos derivados y hacer públicos los datos, imágenes y demás información que los consumidores consignen dentro de la aplicación por cualquier vía, sin perjuicio del deber que tiene DROGUERIASMINIFARMA S.A S. de cumplir con la normativa relacionada con el Tratamiento de Datos Personales.
                        <br>
                        DROGUERIAS MINIFARMA S.A.S. eventualmente utilizará información relacionada con sus tendencias de consumo, ubicación geográfica, precios y demás datos directamente relacionados con las compras realizadas por conducto de los portales, para efectos de ofrecerle productos o servicios relacionados con el sector salud y en general frente a los demás productos que se puedan adquirir por conducto de la aplicación, situación que desde ya el consumidor acepta y condona para los efectos aquí previstos.
                        <br>
                        DROGUERIASMINIFARMA S.A S. se reserva el derecho de eliminar la información consignada por el consumidor dentro de la aplicación en cualquier momento y por cualquier motivo.
                        <br>
                        Para tener más detalles de cómo se manejará su información personal, le recomendamos visitar y aceptar nuestro <a href="#aviso">AVISO DE PRIVACIDAD</a> y las <a href="#tratamiento_datos">POLÍTICAS TRATAMIENTO DE DATOS PERSONALES- DROGUERIAS MINIFARMA S.A.S</a>

                    </p>

                </div>


            </div>
            <div class="col-lg-4 col-sm-12 sm-mt-20">
                <div class="panel">
                    <h1 class="title">Documentos de Interes</h1>

                    <div class="row middle-xs">
                        <!-- <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/technology.svg"
                                alt="phone contact logo"
                            />
                        </div> -->
                        <div class="col-lg-12">
                            <h4 class="com-title"><a href="#aviso">Aviso de privacidad:</a></h4>
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
                            <h4 class="com-title"><a href="#terminos">Términos y Condiciones:</a></h4>
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
                            <h4 class="com-title"><a href="#garantias">Política de Garantías</a></h4>
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
                            <h4 class="com-title"><a href="#devoluciones">Política de Devoluciones</a></h4>
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
                            <h4 class="com-title"><a href="#retracto">Política de Retracto y Reversión</a></h4>
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
                            <h4 class="com-title"><a href="#datos_personales">Manejo de datos Personales</a></h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
