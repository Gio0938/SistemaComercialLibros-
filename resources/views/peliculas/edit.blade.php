@extends('layouts.admin')

@section('title', 'Editar Película - Librería & Cine')
@section('page-title', 'Editar Película')
@section('page-subtitle', 'Actualiza la información de la película')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('peliculas.update', $pelicula->idpelicula) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo', $pelicula->titulo) }}" required>
                                @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Director -->
                            <div class="col-md-6 mb-3">
                                <label for="director" class="form-label">Director <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('director') is-invalid @enderror" id="director" name="director" value="{{ old('director', $pelicula->director) }}" required>
                                @error('director')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Año -->
                            <div class="col-md-3 mb-3">
                                <label for="anio" class="form-label">Año</label>
                                <input type="number" class="form-control @error('anio') is-invalid @enderror" id="anio" name="anio" value="{{ old('anio', $pelicula->anio) }}" min="1888" max="{{ date('Y') + 2 }}">
                                @error('anio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duración -->
                            <div class="col-md-3 mb-3">
                                <label for="duracion" class="form-label">Duración (minutos)</label>
                                <input type="number" class="form-control @error('duracion') is-invalid @enderror" id="duracion" name="duracion" value="{{ old('duracion', $pelicula->duracion) }}" min="0">
                                @error('duracion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Género -->
                            <div class="col-md-3 mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <input type="text" class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero" value="{{ old('genero', $pelicula->genero) }}">
                                @error('genero')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Clasificación -->
                            <div class="col-md-3 mb-3">
                                <label for="clasificacion" class="form-label">Clasificación</label>
                                <select class="form-select @error('clasificacion') is-invalid @enderror" id="clasificacion" name="clasificacion">
                                    <option value="">Seleccionar</option>
                                    <option value="G" {{ old('clasificacion', $pelicula->clasificacion) == 'G' ? 'selected' : '' }}>G (Todos)</option>
                                    <option value="PG" {{ old('clasificacion', $pelicula->clasificacion) == 'PG' ? 'selected' : '' }}>PG (Guía de padres)</option>
                                    <option value="PG-13" {{ old('clasificacion', $pelicula->clasificacion) == 'PG-13' ? 'selected' : '' }}>PG-13 (Mayores 13)</option>
                                    <option value="R" {{ old('clasificacion', $pelicula->clasificacion) == 'R' ? 'selected' : '' }}>R (Restringido)</option>
                                    <option value="NC-17" {{ old('clasificacion', $pelicula->clasificacion) == 'NC-17' ? 'selected' : '' }}>NC-17 (Solo adultos)</option>
                                </select>
                                @error('clasificacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Formato -->
                            <div class="col-md-3 mb-3">
                                <label for="formato" class="form-label">Formato</label>
                                <select class="form-select @error('formato') is-invalid @enderror" id="formato" name="formato">
                                    <option value="DVD" {{ old('formato', $pelicula->formato) == 'DVD' ? 'selected' : '' }}>DVD</option>
                                    <option value="Blu-ray" {{ old('formato', $pelicula->formato) == 'Blu-ray' ? 'selected' : '' }}>Blu-ray</option>
                                    <option value="Digital" {{ old('formato', $pelicula->formato) == 'Digital' ? 'selected' : '' }}>Digital</option>
                                    <option value="VHS" {{ old('formato', $pelicula->formato) == 'VHS' ? 'selected' : '' }}>VHS</option>
                                </select>
                                @error('formato')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Idioma -->
                            <div class="col-md-3 mb-3">
                                <label for="idioma" class="form-label">Idioma</label>
                                <select class="form-select @error('idioma') is-invalid @enderror" id="idioma" name="idioma">
                                    <option value="Español" {{ old('idioma', $pelicula->idioma) == 'Español' ? 'selected' : '' }}>Español</option>
                                    <option value="Inglés" {{ old('idioma', $pelicula->idioma) == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                                    <option value="Francés" {{ old('idioma', $pelicula->idioma) == 'Francés' ? 'selected' : '' }}>Francés</option>
                                    <option value="Alemán" {{ old('idioma', $pelicula->idioma) == 'Alemán' ? 'selected' : '' }}>Alemán</option>
                                    <option value="Portugués" {{ old('idioma', $pelicula->idioma) == 'Portugués' ? 'selected' : '' }}>Portugués</option>
                                </select>
                                @error('idioma')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subtítulos -->
                            <div class="col-md-3 mb-3">
                                <label for="subtitulos" class="form-label">Subtítulos</label>
                                <input type="text" class="form-control @error('subtitulos') is-invalid @enderror" id="subtitulos" name="subtitulos" value="{{ old('subtitulos', $pelicula->subtitulos) }}" placeholder="Ej: Español, Inglés">
                                @error('subtitulos')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reparto -->
                            <div class="col-12 mb-3">
                                <label for="reparto" class="form-label">Reparto</label>
                                <input type="text" class="form-control @error('reparto') is-invalid @enderror" id="reparto" name="reparto" value="{{ old('reparto', $pelicula->reparto) }}" placeholder="Nombres separados por coma">
                                @error('reparto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio -->
                            <div class="col-md-3 mb-3">
                                <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $pelicula->precio) }}" required>
                                @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio Promoción -->
                            <div class="col-md-3 mb-3">
                                <label for="precio_promocion" class="form-label">Precio en Promoción</label>
                                <input type="number" step="0.01" class="form-control @error('precio_promocion') is-invalid @enderror" id="precio_promocion" name="precio_promocion" value="{{ old('precio_promocion', $pelicula->precio_promocion) }}">
                                @error('precio_promocion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="col-md-3 mb-3">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $pelicula->stock) }}" required>
                                @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock Mínimo -->
                            <div class="col-md-3 mb-3">
                                <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                                <input type="number" class="form-control @error('stock_minimo') is-invalid @enderror" id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', $pelicula->stock_minimo) }}">
                                @error('stock_minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sinopsis -->
                            <div class="col-12 mb-3">
                                <label for="sinopsis" class="form-label">Sinopsis</label>
                                <textarea class="form-control @error('sinopsis') is-invalid @enderror" id="sinopsis" name="sinopsis" rows="4">{{ old('sinopsis', $pelicula->sinopsis) }}</textarea>
                                @error('sinopsis')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Trailer URL -->
                            <div class="col-md-6 mb-3">
                                <label for="trailer_url" class="form-label">URL del Trailer</label>
                                <input type="url" class="form-control @error('trailer_url') is-invalid @enderror" id="trailer_url" name="trailer_url" value="{{ old('trailer_url', $pelicula->trailer_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                                @error('trailer_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Portada Actual -->
                            @if($pelicula->portada)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Portada Actual</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $pelicula->portada) }}" alt="{{ $pelicula->titulo }}" style="max-height: 150px; border-radius: 8px;">
                                    </div>
                                </div>
                            @endif

                            <!-- Nueva Portada -->
                            <div class="col-md-6 mb-3">
                                <label for="portada" class="form-label">Nueva Portada</label>
                                <input type="file" class="form-control @error('portada') is-invalid @enderror" id="portada" name="portada" accept="image/*">
                                @error('portada')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Formatos: JPG, PNG, GIF, WebP. Máx 2MB</small>
                            </div>

                            <!-- Opciones -->
                            <div class="col-md-12 mb-3">
                                <div class="d-flex gap-4 pt-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" {{ old('disponible', $pelicula->disponible) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="disponible">Disponible</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="destacado" name="destacado" value="1" {{ old('destacado', $pelicula->destacado) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="destacado">Destacado</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('peliculas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Película
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
