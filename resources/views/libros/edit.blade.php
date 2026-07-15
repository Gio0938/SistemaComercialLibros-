@extends('layouts.admin')

@section('title', 'Editar Libro - Librería & Cine')
@section('page-title', 'Editar Libro')
@section('page-subtitle', 'Actualiza la información del libro')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('libros.update', $libro->idlibro) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo', $libro->titulo) }}" required>
                                @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autor -->
                            <div class="col-md-6 mb-3">
                                <label for="autor" class="form-label">Autor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('autor') is-invalid @enderror" id="autor" name="autor" value="{{ old('autor', $libro->autor) }}" required>
                                @error('autor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Editorial -->
                            <div class="col-md-4 mb-3">
                                <label for="editorial" class="form-label">Editorial</label>
                                <input type="text" class="form-control @error('editorial') is-invalid @enderror" id="editorial" name="editorial" value="{{ old('editorial', $libro->editorial) }}">
                                @error('editorial')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ISBN -->
                            <div class="col-md-4 mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $libro->isbn) }}">
                                @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Género -->
                            <div class="col-md-4 mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <input type="text" class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero" value="{{ old('genero', $libro->genero) }}">
                                @error('genero')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio -->
                            <div class="col-md-3 mb-3">
                                <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $libro->precio) }}" required>
                                @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio Promoción -->
                            <div class="col-md-3 mb-3">
                                <label for="precio_promocion" class="form-label">Precio en Promoción</label>
                                <input type="number" step="0.01" class="form-control @error('precio_promocion') is-invalid @enderror" id="precio_promocion" name="precio_promocion" value="{{ old('precio_promocion', $libro->precio_promocion) }}">
                                @error('precio_promocion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="col-md-3 mb-3">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $libro->stock) }}" required>
                                @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock Mínimo -->
                            <div class="col-md-3 mb-3">
                                <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                                <input type="number" class="form-control @error('stock_minimo') is-invalid @enderror" id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', $libro->stock_minimo) }}">
                                @error('stock_minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Páginas -->
                            <div class="col-md-3 mb-3">
                                <label for="paginas" class="form-label">Páginas</label>
                                <input type="number" class="form-control @error('paginas') is-invalid @enderror" id="paginas" name="paginas" value="{{ old('paginas', $libro->paginas) }}">
                                @error('paginas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Idioma -->
                            <div class="col-md-3 mb-3">
                                <label for="idioma" class="form-label">Idioma</label>
                                <select class="form-select @error('idioma') is-invalid @enderror" id="idioma" name="idioma">
                                    <option value="Español" {{ old('idioma', $libro->idioma) == 'Español' ? 'selected' : '' }}>Español</option>
                                    <option value="Inglés" {{ old('idioma', $libro->idioma) == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                                    <option value="Francés" {{ old('idioma', $libro->idioma) == 'Francés' ? 'selected' : '' }}>Francés</option>
                                    <option value="Alemán" {{ old('idioma', $libro->idioma) == 'Alemán' ? 'selected' : '' }}>Alemán</option>
                                    <option value="Portugués" {{ old('idioma', $libro->idioma) == 'Portugués' ? 'selected' : '' }}>Portugués</option>
                                </select>
                                @error('idioma')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha Publicación -->
                            <div class="col-md-3 mb-3">
                                <label for="fecha_publicacion" class="form-label">Fecha de Publicación</label>
                                <input type="date" class="form-control @error('fecha_publicacion') is-invalid @enderror" id="fecha_publicacion" name="fecha_publicacion" value="{{ old('fecha_publicacion', $libro->fecha_publicacion ? $libro->fecha_publicacion->format('Y-m-d') : '') }}">
                                @error('fecha_publicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $libro->descripcion) }}</textarea>
                                @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Imagen Actual -->
                            @if($libro->foto)
                                <div class="col-12 mb-3">
                                    <label class="form-label">Imagen Actual</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $libro->foto) }}" alt="{{ $libro->titulo }}" style="max-height: 150px; border-radius: 8px;">
                                    </div>
                                </div>
                            @endif

                            <!-- Nueva Imagen -->
                            <div class="col-md-6 mb-3">
                                <label for="foto" class="form-label">Nueva Imagen de Portada</label>
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                                @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Formatos: JPG, PNG, GIF, WebP. Máx 2MB</small>
                            </div>

                            <!-- Opciones -->
                            <div class="col-md-6 mb-3">
                                <div class="d-flex gap-4 pt-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" {{ old('disponible', $libro->disponible) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="disponible">Disponible</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="destacado" name="destacado" value="1" {{ old('destacado', $libro->destacado) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="destacado">Destacado</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('libros.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Libro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
