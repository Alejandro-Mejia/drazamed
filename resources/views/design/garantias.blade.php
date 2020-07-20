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
                    <h1 class="title" > POLÍTICA DE GARANTÍAS Y DEVOLUCIONES DROGUERÍAS DRAZAMED.</h1>
                    <br>
                    Podra descargar una version en PDF de este documento <a href="/assets/pdf/garantias.pdf"> aqui </a>

                    <h2> Temario: </h2>
                    <ol>
                        <li>
                            Definiciones.
                        </li>
                        <li>
                            Requisitos necesarios para aplicar la garantía.
                        </li>
                        <li>
                            Procedimiento para hacer efectiva la garantía.
                        </li>
                        <li>
                            Sobre devoluciones por otros motivos distintos a las que se realizan con ocasión a garantías.
                        </li>
                    </ol>

                    <h2> 1. DEFINICIONES </h2>

                    <ol>
                        <li>
                            <b> Calidad: </b> Condición en que un producto cumple con las características inherentes y las atribuidas por la información que se suministre sobre él.
                        </li>
                        <li>
                            <b>Garantía:</b> Obligación temporal, solidaria a cargo del productor y el proveedor, de responder por el buen estado del producto y la conformidad de este con las condiciones de idoneidad, calidad y seguridad legalmente exigibles o las ofrecidas. La garantía legal no tendrá contraprestación adicional al precio del producto.
                        </li>
                        <li>
                            <b>Idoneidad o eficiencia:</b> Aptitud del producto para satisfacer la necesidad o necesidades para las cuales ha sido producido o comercializado.
                        </li>
                        <li>
                            <b>Producto: </b>Todo bien o servicio.
                        </li>
                        <li>
                            <b>Productor: </b>Quien de manera habitual, directa o indirectamente, diseñe, produzca, fabrique, ensamble o importe productos. También se reputa productor, quien diseñe, produzca, fabrique, ensamble, o importe productos sujetos a reglamento técnico o medida sanitaria o fitosanitaria.
                        </li>
                        <li>
                            <b>Producto defectuoso: </b>es aquel bien mueble o inmueble que debido a un error el diseño, fabricación, construcción, embalaje o información, no ofrezca la razonable seguridad a la que toda persona tiene derecho.
                        </li>
                    </ol>

                    <h2>2.  REQUISITOS NECESARIOS PARA APLICAR LA GARANTÍA.</h2>

                    <ol>
                        <li>
                            <b>Término para hacer efectiva la garantía:</b>   El término previsto para hacer efectiva la garantía es el propuesto por el productor el cual es el que se encuentra descrito en el empaque del producto o en su sitio web, o el término que disponga la ley o la autoridad Administrativa correspondiente en su defecto
                        </li>

                        <li>
                            Causales de exoneración de responsabilidad de la Garantía:  DROGUERÍA MINIFARMA S.A.S, se exonerará de la responsabilidad que se deriva de la garantía, cuando demuestre que el defecto proviene de:

                            <ol>
                                <li>
                                   Fuerza mayor o caso fortuito;
                                </li>
                                <li>
                                    El hecho de un tercero;
                                </li>
                                <li>
                                    El uso indebido del bien por parte del consumidor, y
                                </li>
                                <li>
                                    Que el consumidor no atendió las instrucciones de uso o mantenimiento indicadas en el manual del producto y en la garantía. Para efectos de hacer efectiva esta causal, se deberá tener en cuenta la posología y demás instrucciones de uso y conservación que se encuentren señaladas en el empaque.
                                </li>
                            </ol>

                            Para efectos de alegar la causal DROGUERÍA MINIFARMA S.A.S deberá demostrar el nexo causal entre esta y el defecto con el que cuenta el producto.
                        </li>
                        <li>
                            Requisitos especiales de cada producto: Sumado a los requisitos anteriores, el productor de cada bien establecerá una serie de requisitos para que la garantía opere los cuales se deberán encontrar claramente señalados en el empaque del producto para que estos puedan ser oponibles al consumidor, en el manual certificado de garantía y en defecto de los anteriores, en las disposiciones legales y de carácter administrativo que correspondan.
                        </li>
                    </ol>

                    <h2> 3. PROCEDIMIENTO PARA HACER EFECTIVA LA GARANTÍA: </h2>

                    <p>
                        El procedimiento para hacer efectiva la garantía será el siguiente:

                        <ol>
                            <li>
                                Una vez recibido el producto, el cliente dentro de los cinco (5) días siguientes tendrá la oportunidad de comunicarse con DROGUERIA MINIFARMA S.A.S. para informar su deseo de hacer efectiva la garantía por los siguientes medios:

                                <ol type="a">
                                    <li>
                                        Por medio del correo electrónico  <a href="info@drazamed.com">info@drazamed.com</a>
                                    </li>
                                    <li>
                                        Dejando un mensaje en el apartado de PQR´S de cualquiera de los portales.
                                    </li>
                                </ol>
                            </li>
                            <li>
                                Una vez recibida la solicitud para hacer efectiva la garantía DROGUERIA MINIFARMA S.A.S. deberá responder dentro de los quince (15) días siguientes a la recepción de esta.
                            </li>
                            <li>
                                Si la respuesta es negativa, lo informará presentando los argumentos que sustentan a la misma de conformidad con la normativa aplicable y con la presente política.
                            </li>
                            <li>
                                Si la respuesta es positiva de cara a la efectividad de la garantía DROGUERIA MINIFARMA S.A.S. comunicará al cliente de dicha decisión. En este caso el consumidor deberá devolver el producto a DROGUERIA MINIFARMA S.A.S dentro de los quince (15) días hábiles en la dirección carrera 6 #1 -20 en la ciudad de Cajicá Cundinamarca y deberá asumir los costos que se deriven de dicha actividad.
                            </li>
                            <li>
                                Aceptada la garantía, el consumidor podrá optar entre la reposición del producto o la devolución del dinero. Si el consumidor escoge la reposición, esta se deberá dar dentro de los diez (10) días hábiles siguientes a que el consumidor ponga el producto a disposición de DROGUERIA MINIFARMA S.A.S.
                            </li>
                            <li>
                                Si el consumidor opta por la devolución del dinero, la entrega de este se deberá realizar dentro de los quince (15) días hábiles siguientes. En el caso en que el consumidor desee que la devolución se surta mediante consignación bancaria, este deberá informar los datos necesarios para dicho efecto una vez le sea informada la decisión del productor o proveedor.
                            </li>
                            <li>
                                El consumidor es libre de contactar y poner el producto en manos directamente del Productor del mismo para hacer efectiva la garantía respectiva.
                            </li>
                        </ol>

                        El mismo procedimiento aplicará cuando la devolución se quiere hacer por un error de DROGUERIA MINIFARMA S.A.S. al momento de la entrega del producto adquirido por el consumidor.
                    </p>

                    <h2> 4. SOBRE DEVOLUCIONES POR OTROS MOTIVOS DISTINTOS A LAS QUE SE REALIZAN CON OCASIÓN A GARANTÍAS. </h2>

                    <p>
                        DROGUERIA MINIFARMA S.A.S. no realizará devoluciones o cambios de productos que se encuentren por fuera del marco de la efectividad de las garantías legales y las propuestas expresamente por los productores de cada producto vendido.
                    </p>

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
