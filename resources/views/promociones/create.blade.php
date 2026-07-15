@extends('layouts.admin')

@section('title', 'Nueva Promoción - Librería & Cine')
@section('page-title', 'Crear Nueva Promoción')
@section('page-subtitle', 'Define los descuentos y promociones')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('promociones.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre de la Promoción <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Código Promocional -->
                            <div class="col-md-6 mb-3">
                                <label for="codigo_promocional" class="form-label">Código Promocional</label>
                                <input type="text" class="form-control @error('codigo_promocional') is-invalid @enderror" id="codigo_promocional" name="codigo_promocional" value="{{ old('codigo_promocional') }}" placeholder="Ej: DESCUENTO20">
                                @error('codigo_promocional')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Código único para que los clientes apliquen la promoción</small>
                            </div>

                            <!-- Descripción -->
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div class="col-md-4 mb-3">
                                <label for="tipo" class="form-label">Tipo de Promoción <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar</option>
                                    <option value="libro" {{ old('tipo') == 'libro' ? 'selected' : '' }}>Libros</option>
                                    <option value="pelicula" {{ old('tipo') == 'pelicula' ? 'selected' : '' }}>Películas</option>
                                    <option value="ambos" {{ old('tipo') == 'ambos' ? 'selected' : '' }}>Ambos</option>
                                </select>
                                @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Libro -->
                            <div class="col-md-4 mb-3" id="libroContainer" style="display: none;">
                                <label for="libro_id" class="form-label">Libro <span class="text-danger">*</span></label>
                                <select class="form-select @error('libro_id') is-invalid @enderror" id="libro_id" name="libro_id">
                                    <option value="">Seleccionar libro</option>
                                    @foreach($libros ?? [] as $libro)
                                        <option value="{{ $libro->idlibro }}" {{ old('libro_id') == $libro->idlibro ? 'selected' : '' }}>
                                            {{ $libro->titulo }} - {{ $libro->autor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('libro_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Película -->
                            <div class="col-md-4 mb-3" id="peliculaContainer" style="display: none;">
                                <label for="pelicula_id" class="form-label">Película <span class="text-danger">*</span></label>
                                <select class="form-select @error('pelicula_id') is-invalid @enderror" id="pelicula_id" name="pelicula_id">
                                    <option value="">Seleccionar película</option>
                                    @foreach($peliculas ?? [] as $pelicula)
                                        <option value="{{ $pelicula->idpelicula }}" {{ old('pelicula_id') == $pelicula->idpelicula ? 'selected' : '' }}>
                                            {{ $pelicula->titulo }} - {{ $pelicula->director }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelicula_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo de Descuento -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tipo de Descuento <span class="text-danger">*</span></label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="descuento_porcentaje" name="tipo_descuento" value="porcentaje" {{ old('tipo_descuento') == 'porcentaje' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="descuento_porcentaje">Porcentaje</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="descuento_fijo" name="tipo_descuento" value="fijo" {{ old('tipo_descuento') == 'fijo' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="descuento_fijo">Monto Fijo</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Descuento Porcentaje -->
                            <div class="col-md-4 mb-3" id="porcentajeContainer">
                                <label for="descuento_porcentaje" class="form-label">Porcentaje de Descuento</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('descuento_porcentaje') is-invalid @enderror" id="descuento_porcentaje" name="descuento_porcentaje" value="{{ old('descuento_porcentaje') }}" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('descuento_porcentaje')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descuento Fijo -->
                            <div class="col-md-4 mb-3" id="fijoContainer" style="display: none;">
                                <label for="descuento_fijo" class="form-label">Monto de Descuento</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('descuento_fijo') is-invalid @enderror" id="descuento_fijo" name="descuento_fijo" value="{{ old('descuento_fijo') }}" min="0">
                                </div>
                                @error('descuento_fijo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fechas -->
                            <div class="col-md-6 mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                                @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                                @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Uso Máximo -->
                            <div class="col-md-6 mb-3">
                                <label for="uso_maximo" class="form-label">Uso Máximo</label>
                                <input type="number" class="form-control @error('uso_maximo') is-invalid @enderror" id="uso_maximo" name="uso_maximo" value="{{ old('uso_maximo') }}" min="1" placeholder="Dejar vacío para uso ilimitado">
                                @error('uso_maximo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Número máximo de veces que se puede usar esta promoción</small>
                            </div>

                            <!-- Activa -->
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="activa" name="activa" value="1" {{ old('activa') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activa">Activar promoción inmediatamente</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('promociones.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Promoción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Mostrar/ocultar campos de producto según tipo
            $('#tipo').on('change', function() {
                const tipo = $(this).val();
                if (tipo === 'libro') {
                    $('#libroContainer').show();
                    $('#peliculaContainer').hide();
                    $('#libro_id').prop('required', true);
                    $('#pelicula_id').prop('required', false);
                } else if (tipo === 'pelicula') {
                    $('#libroContainer').hide();
                    $('#peliculaContainer').show();
                    $('#libro_id').prop('required', false);
                    $('#pelicula_id').prop('required', true);
                } else if (tipo === 'ambos') {
                    $('#libroContainer').hide();
                    $('#peliculaContainer').hide();
                    $('#libro_id').prop('required', false);
                    $('#pelicula_id').prop('required', false);
                } else {
                    $('#libroContainer').hide();
                    $('#peliculaContainer').hide();
                    $('#libro_id').prop('required', false);
                    $('#pelicula_id').prop('required', false);
                }
            });

            // Mostrar/ocultar campos de descuento según tipo
            $('input[name="tipo_descuento"]').on('change', function() {
                const tipo = $(this).val();
                if (tipo === 'porcentaje') {
                    $('#porcentajeContainer').show();
                    $('#fijoContainer').hide();
                    $('#descuento_porcentaje').prop('required', true);
                    $('#descuento_fijo').prop('required', false);
                } else if (tipo === 'fijo') {
                    $('#porcentajeContainer').hide();
                    $('#fijoContainer').show();
                    $('#descuento_porcentaje').prop('required', false);
                    $('#descuento_fijo').prop('required', true);
                }
            });

            // Trigger para cargar el estado inicial
            $('#tipo').trigger('change');
            $('input[name="tipo_descuento"]:checked').trigger('change');
        });
    </script>
@endpush
