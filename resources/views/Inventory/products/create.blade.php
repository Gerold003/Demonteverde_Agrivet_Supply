@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Add New Product</h1>
        <a href="{{ route('inventory.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Products
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Product Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventory.products.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                       id="brand" name="brand" value="{{ old('brand') }}">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price_per_sack" class="form-label">Price per Sack</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('price_per_sack') is-invalid @enderror"
                                           id="price_per_sack" name="price_per_sack"
                                           value="{{ old('price_per_sack') }}">
                                </div>
                                @error('price_per_sack')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price_per_kilo" class="form-label">Price per Kilo</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('price_per_kilo') is-invalid @enderror"
                                           id="price_per_kilo" name="price_per_kilo"
                                           value="{{ old('price_per_kilo') }}">
                                </div>
                                @error('price_per_kilo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price_per_piece" class="form-label">Price per Piece</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('price_per_piece') is-invalid @enderror"
                                           id="price_per_piece" name="price_per_piece"
                                           value="{{ old('price_per_piece') }}">
                                </div>
                                @error('price_per_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card border-warning mb-4">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="mb-0">Stock Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="current_stock_sack" class="form-label">Current Stock (Sacks)</label>
                                        <input type="number" min="0"
                                               class="form-control @error('current_stock_sack') is-invalid @enderror"
                                               id="current_stock_sack" name="current_stock_sack"
                                               value="{{ old('current_stock_sack', 0) }}">
                                        @error('current_stock_sack')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="current_stock_kilo" class="form-label">Current Stock (Kilos)</label>
                                        <input type="number" step="0.01" min="0"
                                               class="form-control @error('current_stock_kilo') is-invalid @enderror"
                                               id="current_stock_kilo" name="current_stock_kilo"
                                               value="{{ old('current_stock_kilo', 0) }}">
                                        @error('current_stock_kilo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="current_stock_piece" class="form-label">Current Stock (Pieces)</label>
                                        <input type="number" min="0"
                                               class="form-control @error('current_stock_piece') is-invalid @enderror"
                                               id="current_stock_piece" name="current_stock_piece"
                                               value="{{ old('current_stock_piece', 0) }}">
                                        @error('current_stock_piece')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-danger mb-4">
                            <div class="card-header bg-danger bg-opacity-10">
                                <h6 class="mb-0">Critical Level Alerts</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="critical_level_sack" class="form-label">Critical Level (Sacks)</label>
                                        <input type="number" min="0"
                                               class="form-control @error('critical_level_sack') is-invalid @enderror"
                                               id="critical_level_sack" name="critical_level_sack"
                                               value="{{ old('critical_level_sack', 2) }}"
                                               placeholder="Default: 2">
                                        @error('critical_level_sack')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Alert when stock falls below this level</small>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="critical_level_kilo" class="form-label">Critical Level (Kilos)</label>
                                        <input type="number" step="0.01" min="0"
                                               class="form-control @error('critical_level_kilo') is-invalid @enderror"
                                               id="critical_level_kilo" name="critical_level_kilo"
                                               value="{{ old('critical_level_kilo', 0) }}"
                                               placeholder="Optional">
                                        @error('critical_level_kilo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="critical_level_piece" class="form-label">Critical Level (Pieces)</label>
                                        <input type="number" min="0"
                                               class="form-control @error('critical_level_piece') is-invalid @enderror"
                                               id="critical_level_piece" name="critical_level_piece"
                                               value="{{ old('critical_level_piece', 0) }}"
                                               placeholder="Optional">
                                        @error('critical_level_piece')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('inventory.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
