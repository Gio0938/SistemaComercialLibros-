@extends('layouts.admin')

@section('title', 'Punto de Venta - Librería & Cine')
@section('page-title', 'Punto de Venta')
@section('page-subtitle', 'Registra una nueva venta')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <!-- Formulario de Venta -->
                    <form id="ventaForm" action="{{ route('ventas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="folio" value="{{ $nuevoFolio }}">

                        <!-- Datos del Cliente -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cliente_nombre" class="form-label">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" placeholder="Público en general">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cliente_rfc" class="form-label">RFC</label>
                                <input type="text" class="form-control" id="cliente_rfc" name="cliente_rfc" placeholder="RFC">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cliente_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="cliente_telefono" name="cliente_telefono" placeholder="Teléfono">
                            </div>
                        </div>

                        <hr>

                        <!-- Selección de Producto -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_producto" class="form-label">Tipo de Producto <span class="text-danger">*</span></label>
                                <select class="form-select" id="tipo_producto" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="libro">Libro</option>
                                    <option value="pelicula">Película</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="producto_id" class="form-label">Producto <span class="text-danger">*</span></label>
                                <select class="form-select" id="producto_id" required>
                                    <option value="">Primero selecciona un tipo</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campos dinámicos según tipo de producto -->
                        <div id="camposDinamicos">
                            <!-- Los campos se cargan dinámicamente aquí -->
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="precio_unitario" class="form-label">Precio Unitario</label>
                                <input type="number" step="0.01" class="form-control" id="precio_unitario" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="stock_disponible" class="form-label">Stock Disponible</label>
                                <input type="text" class="form-control" id="stock_disponible" readonly>
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="btnAgregar">
                                    <i class="fas fa-plus me-2"></i>Agregar
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="metodo_pago" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="credito">Crédito</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="1"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Carrito de Compras -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Carrito</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="carritoBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-shopping-cart fa-2x d-block mb-2"></i>
                                    Carrito vacío
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Subtotal</small>
                            <h5 id="subtotalDisplay">$0.00</h5>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">IVA (16%)</small>
                            <h5 id="ivaDisplay">$0.00</h5>
                        </div>
                        <div class="col-12">
                            <hr class="my-1">
                            <div class="d-flex justify-content-between">
                                <strong>Total</strong>
                                <h4 class="text-success" id="totalDisplay">$0.00</h4>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-success w-100" id="btnFinalizarVenta" disabled>
                                <i class="fas fa-check me-2"></i>Finalizar Venta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar venta -->
    <div class="modal fade" id="confirmarVentaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de finalizar esta venta?</p>
                    <div class="row">
                        <div class="col-6">
                            <small>Total</small>
                            <h4 id="modalTotal">$0.00</h4>
                        </div>
                        <div class="col-6">
                            <small>Artículos</small>
                            <h4 id="modalItems">0</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarVenta">
                        <i class="fas fa-check me-2"></i>Confirmar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-card {
            cursor: pointer;
            transition: all 0.2s;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .product-card.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }
        .carrito-item {
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .btn-remove {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0 5px;
        }
        .btn-remove:hover {
            color: #a71d2a;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let carrito = [];
            let productosData = [];

            // ============================================
            // CARGAR PRODUCTOS POR TIPO
            // ============================================
            $('#tipo_producto').on('change', function() {
                const tipo = $(this).val();
                const $productoSelect = $('#producto_id');
                const $camposDinamicos = $('#camposDinamicos');

                $productoSelect.empty().append('<option value="">Seleccionar producto...</option>');
                $camposDinamicos.empty();
                $('#precio_unitario').val('');
                $('#stock_disponible').val('');

                if (!tipo) return;

                // Cargar productos según tipo
                $.ajax({
                    url: tipo === 'libro' ? '/api/libros-venta' : '/api/peliculas-venta',
                    method: 'GET',
                    success: function(data) {
                        productosData = data;
                        data.forEach(function(item) {
                            const label = tipo === 'libro'
                                ? item.titulo + ' - ' + item.autor + ' ($' + parseFloat(item.precio).toFixed(2) + ')'
                                : item.titulo + ' - ' + item.director + ' ($' + parseFloat(item.precio).toFixed(2) + ')';
                            $productoSelect.append('<option value="' + item.id + '" data-precio="' + item.precio + '" data-stock="' + item.stock + '">' + label + '</option>');
                        });

                        // Generar campos dinámicos según tipo
                        generarCamposDinamicos(tipo);
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudieron cargar los productos', 'error');
                    }
                });
            });

            // ============================================
            // GENERAR CAMPOS DINÁMICOS
            // ============================================
            function generarCamposDinamicos(tipo) {
                const $container = $('#camposDinamicos');
                $container.empty();

                if (tipo === 'libro') {
                    $container.append(`
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="libro_genero" class="form-label">Género</label>
                        <input type="text" class="form-control" id="libro_genero" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="libro_editorial" class="form-label">Editorial</label>
                        <input type="text" class="form-control" id="libro_editorial" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="libro_autor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="libro_autor" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="libro_isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="libro_isbn" readonly>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="libro_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="libro_descripcion" rows="2" readonly></textarea>
                    </div>
                </div>
            `);
                } else if (tipo === 'pelicula') {
                    $container.append(`
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pelicula_director" class="form-label">Director</label>
                        <input type="text" class="form-control" id="pelicula_director" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pelicula_anio" class="form-label">Año</label>
                        <input type="text" class="form-control" id="pelicula_anio" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pelicula_formato" class="form-label">Formato</label>
                        <input type="text" class="form-control" id="pelicula_formato" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pelicula_clasificacion" class="form-label">Clasificación</label>
                        <input type="text" class="form-control" id="pelicula_clasificacion" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pelicula_duracion" class="form-label">Duración</label>
                        <input type="text" class="form-control" id="pelicula_duracion" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pelicula_idioma" class="form-label">Idioma</label>
                        <input type="text" class="form-control" id="pelicula_idioma" readonly>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="pelicula_sinopsis" class="form-label">Sinopsis</label>
                        <textarea class="form-control" id="pelicula_sinopsis" rows="2" readonly></textarea>
                    </div>
                </div>
            `);
                }
            }

            // ============================================
            // SELECCIONAR PRODUCTO - CARGAR DATOS
            // ============================================
            $('#producto_id').on('change', function() {
                const $selected = $(this).find(':selected');
                const precio = $selected.data('precio');
                const stock = $selected.data('stock');
                const tipo = $('#tipo_producto').val();
                const id = $selected.val();

                $('#precio_unitario').val(precio ? parseFloat(precio).toFixed(2) : '');
                $('#stock_disponible').val(stock ? stock : '0');

                // Cargar datos adicionales según tipo
                if (id && tipo) {
                    const producto = productosData.find(p => p.id == id);
                    if (producto) {
                        if (tipo === 'libro') {
                            $('#libro_genero').val(producto.genero || 'N/A');
                            $('#libro_editorial').val(producto.editorial || 'N/A');
                            $('#libro_autor').val(producto.autor || 'N/A');
                            $('#libro_isbn').val(producto.isbn || 'N/A');
                            $('#libro_descripcion').val(producto.descripcion || 'Sin descripción');
                        } else if (tipo === 'pelicula') {
                            $('#pelicula_director').val(producto.director || 'N/A');
                            $('#pelicula_anio').val(producto.anio || 'N/A');
                            $('#pelicula_formato').val(producto.formato || 'N/A');
                            $('#pelicula_clasificacion').val(producto.clasificacion || 'N/A');
                            $('#pelicula_duracion').val(producto.duracion ? producto.duracion + ' min' : 'N/A');
                            $('#pelicula_idioma').val(producto.idioma || 'N/A');
                            $('#pelicula_sinopsis').val(producto.sinopsis || 'Sin sinopsis');
                        }
                    }
                }
            });

            // ============================================
            // AGREGAR AL CARRITO
            // ============================================
            $('#btnAgregar').on('click', function() {
                const tipo = $('#tipo_producto').val();
                const productoId = $('#producto_id').val();
                const cantidad = parseInt($('#cantidad').val()) || 1;
                const precio = parseFloat($('#precio_unitario').val()) || 0;
                const stock = parseInt($('#stock_disponible').val()) || 0;

                if (!tipo || !productoId) {
                    Swal.fire('Error', 'Selecciona un producto', 'warning');
                    return;
                }

                if (cantidad <= 0) {
                    Swal.fire('Error', 'La cantidad debe ser mayor a 0', 'warning');
                    return;
                }

                if (cantidad > stock) {
                    Swal.fire('Error', 'No hay suficiente stock disponible. Stock: ' + stock, 'warning');
                    return;
                }

                const producto = productosData.find(p => p.id == productoId);
                if (!producto) {
                    Swal.fire('Error', 'Producto no encontrado', 'error');
                    return;
                }

                // Verificar si ya existe en el carrito
                const existente = carrito.find(item => item.id === productoId && item.tipo === tipo);
                if (existente) {
                    if (existente.cantidad + cantidad > stock) {
                        Swal.fire('Error', 'No hay suficiente stock disponible. Stock: ' + stock, 'warning');
                        return;
                    }
                    existente.cantidad += cantidad;
                    existente.subtotal = existente.cantidad * existente.precio;
                } else {
                    carrito.push({
                        id: productoId,
                        tipo: tipo,
                        nombre: tipo === 'libro' ? producto.titulo : producto.titulo,
                        autor: tipo === 'libro' ? producto.autor : producto.director,
                        cantidad: cantidad,
                        precio: precio,
                        subtotal: cantidad * precio,
                        stock: stock
                    });
                }

                actualizarCarrito();
                $('#cantidad').val(1);
                Swal.fire({
                    icon: 'success',
                    title: 'Agregado',
                    text: 'Producto agregado al carrito',
                    timer: 1000,
                    showConfirmButton: false
                });
            });

            // ============================================
            // ACTUALIZAR CARRITO
            // ============================================
            function actualizarCarrito() {
                const $body = $('#carritoBody');
                let subtotal = 0;

                if (carrito.length === 0) {
                    $body.html('<tr><td colspan="5" class="text-center text-muted py-3"><i class="fas fa-shopping-cart fa-2x d-block mb-2"></i>Carrito vacío</td></tr>');
                    $('#subtotalDisplay').text('$0.00');
                    $('#ivaDisplay').text('$0.00');
                    $('#totalDisplay').text('$0.00');
                    $('#btnFinalizarVenta').prop('disabled', true);
                    return;
                }

                let html = '';
                carrito.forEach((item, index) => {
                    subtotal += item.subtotal;
                    const nombre = item.nombre + (item.autor ? ' (' + item.autor + ')' : '');
                    html += `
                <tr class="carrito-item">
                    <td>
                        <span class="badge bg-${item.tipo === 'libro' ? 'primary' : 'info'} me-1">${item.tipo === 'libro' ? '📚' : '🎬'}</span>
                        ${item.nombre.length > 15 ? item.nombre.substring(0, 15) + '...' : item.nombre}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary btn-cantidad" data-index="${index}" data-cambio="-1">-</button>
                        <span class="mx-1">${item.cantidad}</span>
                        <button class="btn btn-sm btn-outline-secondary btn-cantidad" data-index="${index}" data-cambio="1">+</button>
                    </td>
                    <td>$${item.precio.toFixed(2)}</td>
                    <td>$${item.subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn-remove btn-remove-item" data-index="${index}" title="Eliminar">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
                });

                $body.html(html);

                // Calcular totales
                const iva = subtotal * 0.16;
                const total = subtotal + iva;

                $('#subtotalDisplay').text('$' + subtotal.toFixed(2));
                $('#ivaDisplay').text('$' + iva.toFixed(2));
                $('#totalDisplay').text('$' + total.toFixed(2));

                $('#btnFinalizarVenta').prop('disabled', false);

                // Eventos de cantidad
                $('.btn-cantidad').on('click', function() {
                    const index = parseInt($(this).data('index'));
                    const cambio = parseInt($(this).data('cambio'));
                    const item = carrito[index];
                    const nuevaCantidad = item.cantidad + cambio;

                    if (nuevaCantidad < 1) return;
                    if (nuevaCantidad > item.stock) {
                        Swal.fire('Error', 'No hay suficiente stock disponible. Stock: ' + item.stock, 'warning');
                        return;
                    }

                    item.cantidad = nuevaCantidad;
                    item.subtotal = item.cantidad * item.precio;
                    actualizarCarrito();
                });

                // Eventos de eliminar
                $('.btn-remove-item').on('click', function() {
                    const index = parseInt($(this).data('index'));
                    const item = carrito[index];
                    Swal.fire({
                        title: '¿Eliminar producto?',
                        text: '¿Estás seguro de eliminar "' + item.nombre + '" del carrito?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            carrito.splice(index, 1);
                            actualizarCarrito();
                        }
                    });
                });
            }

            // ============================================
            // FINALIZAR VENTA
            // ============================================
            $('#btnFinalizarVenta').on('click', function() {
                if (carrito.length === 0) {
                    Swal.fire('Error', 'El carrito está vacío', 'warning');
                    return;
                }

                // Calcular totales
                let subtotal = 0;
                carrito.forEach(item => subtotal += item.subtotal);
                const iva = subtotal * 0.16;
                const total = subtotal + iva;

                // Mostrar modal de confirmación
                $('#modalTotal').text('$' + total.toFixed(2));
                $('#modalItems').text(carrito.reduce((sum, item) => sum + item.cantidad, 0));
                $('#confirmarVentaModal').modal('show');
            });

            // ============================================
            // CONFIRMAR VENTA
            // ============================================
            $('#btnConfirmarVenta').on('click', function() {
                const $btn = $(this);
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Procesando...');

                // Preparar datos
                const clienteNombre = $('#cliente_nombre').val() || 'Público en general';
                const clienteRfc = $('#cliente_rfc').val() || '';
                const clienteTelefono = $('#cliente_telefono').val() || '';

                // Calcular totales
                let subtotal = 0;
                carrito.forEach(item => subtotal += item.subtotal);
                const total = subtotal + (subtotal * 0.16);

                const items = carrito.map(item => ({
                    tipo: item.tipo,
                    id: item.id,
                    cantidad: item.cantidad,
                    precio: item.precio
                }));

                const data = {
                    folio: $('input[name="folio"]').val(),
                    cliente_nombre: clienteNombre,
                    cliente_rfc: clienteRfc,
                    cliente_telefono: clienteTelefono,
                    metodo_pago: $('#metodo_pago').val(),
                    observaciones: $('#observaciones').val(),
                    items: items,
                    total: total
                };

                // Enviar al servidor
                $.ajax({
                    url: $('#ventaForm').attr('action'),
                    method: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#confirmarVentaModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: '¡Venta registrada!',
                                text: 'Folio: ' + response.folio,
                                showCancelButton: true,
                                confirmButtonText: 'Ver Ticket',
                                cancelButtonText: 'Nueva Venta'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.open('/ventas/ticket/' + response.venta_id, '_blank');
                                }
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Error al procesar la venta', 'error');
                        }
                    },
                    error: function(xhr) {
                        let mensaje = 'Error al procesar la venta';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            mensaje = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', mensaje, 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<i class="fas fa-check me-2"></i>Confirmar Venta');
                    }
                });
            });

            // ============================================
            // ENTER PARA AGREGAR PRODUCTO
            // ============================================
            $('#cantidad').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btnAgregar').click();
                }
            });

            // ============================================
            // KEYBOARD SHORTCUTS
            // ============================================
            $(document).on('keydown', function(e) {
                // Ctrl + Enter para finalizar venta
                if (e.ctrlKey && e.which === 13) {
                    e.preventDefault();
                    $('#btnFinalizarVenta').click();
                }
            });
        });
    </script>
@endpush
