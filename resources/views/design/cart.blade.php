@extends('design.layout.app') @section('custom-css')
<link rel="stylesheet" href="/css/cart.css" />
@endsection @section('content')

<main>
    <div class="cart-section">
        <div class="row">
            <div class="col-md-8">
                <div class="cart">
                    <h3 class="dra-color cart-title">
                        Carrito de Compras
                    </h3>
                    <p class="text-justify">
                        Si termino de adicionar medicamentos a su carro de
                        compras, por favor busque y cargue la formula medica en
                        el espacio de abajo. Usted tambien puede subir una
                        formula medica, sin agregar medicamentos a su carrito.
                        Nosotros identificaremos los medicamentos y procesaremos
                        su orden.
                    </p>

                    <table class="table my-4">
                        <thead>
                            <tr>
                                <th scope="col">ITEM</th>
                                <th scope="col">CANTIDAD</th>
                                <th scope="col">PRECIO POR UNIDAD</th>
                                <th scope="col">DESCUENTO POR UNIDAD</th>
                                <th scope="col">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="font-weight-normal">
                                    Nombre del medicamento
                                </th>
                                <td>
                                    <input
                                        class="med-quantity-input"
                                        type="number"
                                        min="1"
                                        value="1"
                                    />
                                </td>
                                <td>$</td>
                                <td>$</td>
                                <td>$</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="upload-zone text-center">
                        <span class="dra-color fas fa-cloud-upload-alt"></span>
                        <p class="text-black-50">
                            Adjunta a continuación tu formula médica
                        </p>
                        <button class="mt-2 dra-button">Subir Archivo</button>
                    </div>

                    <button class="float-right mt-4 dra-button">
                        Realizar Pedido
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dra-box">
                    <h3 class="mb-4">Buscar</h3>
                    <form action="" class="form-search-med">
                        <div
                            class="input-group mb-3 back_ground_loader row-search ui-widget"
                        >
                            <input
                                type="text"
                                class="form-control search_medicine"
                                placeholder="Busca un medicamento por nombre"
                                aria-label="Busca un medicamento por nombre"
                                aria-describedby="basic-addon2"
                                id="search_medicine"
                            />
                            <div class="input-group-append">
                                <span
                                    class="input-group-text btn-med-search"
                                    id="basic-addon2"
                                    ><span
                                        class="fas fa-search"
                                        id="searchButton"
                                    ></span
                                ></span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="dra-box mt-4">
                    <h3 class="mb-4">Productos Relacionados</h3>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
